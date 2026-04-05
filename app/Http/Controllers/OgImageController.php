<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Typography\FontFactory;

class OgImageController extends Controller
{
    private const WIDTH = 1200;

    private const HEIGHT = 630;

    public function __invoke(Post $post): Response
    {
        $cacheKey = "og-image-{$post->id}-{$post->updated_at->timestamp}";

        $imageData = Cache::remember($cacheKey, now()->addDays(7), function () use ($post) {
            return $this->generate($post);
        });

        return response($imageData, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }

    private function generate(Post $post): string
    {
        $manager = new ImageManager(Driver::class);
        $canvas = $manager->createImage(self::WIDTH, self::HEIGHT);

        $canvas->fill('#0a0c12');

        $this->drawGradientBackground($canvas);

        // Featured image on the right side if available
        $contentRightBound = self::WIDTH - 80;
        if ($post->featured_image && Storage::disk('public')->exists($post->featured_image)) {
            $this->drawFeaturedImage($canvas, $manager, $post);
            $contentRightBound = 680;
        }

        // Decorative amber accent line at top
        $canvas->drawRectangle(function ($draw) {
            $draw->size(60, 4);
            $draw->at(80, 80);
            $draw->background('#c9a872');
        });

        // Post title
        $titleY = $this->drawTitle($canvas, $post->title, $contentRightBound);

        // Excerpt (if available and space permits)
        if ($post->excerpt && $titleY < 380) {
            $titleY = $this->drawExcerpt($canvas, $post->excerpt, $titleY + 24, $contentRightBound);
        }

        // Tags
        if ($post->relationLoaded('tags') ? $post->tags->isNotEmpty() : $post->tags()->exists()) {
            $tags = $post->relationLoaded('tags') ? $post->tags : $post->tags()->get();
            $this->drawTags($canvas, $tags->pluck('name')->take(3)->toArray(), min($titleY + 30, 480));
        }

        // Bottom branding bar
        $this->drawBranding($canvas);

        return $canvas->encode(new PngEncoder)->toString();
    }

    private function drawGradientBackground(ImageInterface $canvas): void
    {
        // Subtle teal glow in bottom-right
        $canvas->drawEllipse(function ($draw) {
            $draw->size(600, 400);
            $draw->at(self::WIDTH - 100, self::HEIGHT + 50);
            $draw->background('rgba(91, 168, 168, 0.05)');
        });

        // Subtle amber glow top-left
        $canvas->drawEllipse(function ($draw) {
            $draw->size(500, 350);
            $draw->at(100, -50);
            $draw->background('rgba(201, 168, 114, 0.04)');
        });

        // Subtle horizontal scan lines for texture
        for ($i = 0; $i < self::HEIGHT; $i += 4) {
            $alpha = ($i % 8 === 0) ? 0.03 : 0.015;
            $canvas->drawLine(function ($draw) use ($i, $alpha) {
                $draw->from(0, $i);
                $draw->to(self::WIDTH, $i);
                $draw->color("rgba(255, 255, 255, {$alpha})");
                $draw->width(1);
            });
        }
    }

    private function drawFeaturedImage(ImageInterface $canvas, ImageManager $manager, Post $post): void
    {
        $imagePath = Storage::disk('public')->path($post->featured_image);
        $featured = $manager->decode(file_get_contents($imagePath));

        $featured = $featured->cover(440, 470);

        // Place on the right side
        $canvas->insert($featured, 680, 80);

        // Semi-transparent overlay to blend with background
        $canvas->drawRectangle(function ($draw) {
            $draw->size(440, 470);
            $draw->at(680, 80);
            $draw->background('rgba(10, 12, 18, 0.3)');
        });

        // Left edge fade — progressively more opaque strips
        for ($i = 0; $i < 80; $i++) {
            $opacity = round(1 - $i / 80, 2);
            $canvas->drawLine(function ($draw) use ($i, $opacity) {
                $draw->from(680 + $i, 80);
                $draw->to(680 + $i, 550);
                $draw->color("rgba(10, 12, 18, {$opacity})");
                $draw->width(1);
            });
        }
    }

    private function drawTitle(ImageInterface $canvas, string $title, int $rightBound): int
    {
        $fontPath = resource_path('fonts/Syne-ExtraBold.ttf');
        $maxWidth = $rightBound - 80;

        $fontSize = strlen($title) > 60 ? 38 : 46;
        $lines = $this->wordWrap($title, $fontPath, $fontSize, $maxWidth);

        $y = 110;
        $lineHeight = (int) ($fontSize * 1.25);

        foreach ($lines as $line) {
            $canvas->text($line, 80, $y, function (FontFactory $font) use ($fontPath, $fontSize) {
                $font->filename($fontPath);
                $font->size($fontSize);
                $font->color('#e4e2de');
                $font->align('left', 'top');
            });
            $y += $lineHeight;
        }

        return $y;
    }

    private function drawExcerpt(ImageInterface $canvas, string $excerpt, int $startY, int $rightBound): int
    {
        $fontPath = resource_path('fonts/Newsreader-Regular.ttf');
        $maxWidth = $rightBound - 80;
        $fontSize = 20;

        $excerpt = strlen($excerpt) > 160 ? substr($excerpt, 0, 157).'...' : $excerpt;
        $lines = $this->wordWrap($excerpt, $fontPath, $fontSize, $maxWidth);
        $lines = array_slice($lines, 0, 3);

        $y = $startY;
        $lineHeight = (int) ($fontSize * 1.5);

        foreach ($lines as $line) {
            $canvas->text($line, 80, $y, function (FontFactory $font) use ($fontPath, $fontSize) {
                $font->filename($fontPath);
                $font->size($fontSize);
                $font->color('#8a8f9c');
                $font->align('left', 'top');
            });
            $y += $lineHeight;
        }

        return $y;
    }

    private function drawTags(ImageInterface $canvas, array $tags, int $y): void
    {
        $fontPath = resource_path('fonts/Syne-Bold.ttf');
        $x = 80;

        foreach ($tags as $tag) {
            // Tag dot
            $canvas->drawEllipse(function ($draw) use ($x, $y) {
                $draw->size(8, 8);
                $draw->at($x + 5, $y + 10);
                $draw->background('#c9a872');
            });

            $x += 16;

            $canvas->text($tag, $x, $y, function (FontFactory $font) use ($fontPath) {
                $font->filename($fontPath);
                $font->size(16);
                $font->color('#b8bcc6');
                $font->align('left', 'top');
            });

            $x += (int) (strlen($tag) * 9.5) + 20;
        }
    }

    private function drawBranding(ImageInterface $canvas): void
    {
        // Bottom bar background
        $canvas->drawRectangle(function ($draw) {
            $draw->size(self::WIDTH, 70);
            $draw->at(0, self::HEIGHT - 70);
            $draw->background('rgba(10, 12, 18, 0.8)');
        });

        // Top border line
        $canvas->drawLine(function ($draw) {
            $draw->from(80, self::HEIGHT - 70);
            $draw->to(self::WIDTH - 80, self::HEIGHT - 70);
            $draw->color('rgba(29, 34, 52, 0.8)');
            $draw->width(1);
        });

        $fontPath = resource_path('fonts/Syne-Bold.ttf');

        // Author name
        $canvas->text('Mack Hankins', 80, self::HEIGHT - 48, function (FontFactory $font) use ($fontPath) {
            $font->filename($fontPath);
            $font->size(18);
            $font->color('#e4e2de');
            $font->align('left', 'top');
        });

        // Domain
        $canvas->text('mackhankins.com', self::WIDTH - 80, self::HEIGHT - 48, function (FontFactory $font) use ($fontPath) {
            $font->filename($fontPath);
            $font->size(16);
            $font->color('#555b6e');
            $font->align('right', 'top');
        });
    }

    /**
     * @return string[]
     */
    private function wordWrap(string $text, string $fontPath, int $fontSize, int $maxWidth): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = $currentLine === '' ? $word : $currentLine.' '.$word;
            $box = imagettfbbox($fontSize, 0, $fontPath, $testLine);

            if ($box && ($box[2] - $box[0]) > $maxWidth && $currentLine !== '') {
                $lines[] = $currentLine;
                $currentLine = $word;
            } else {
                $currentLine = $testLine;
            }
        }

        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        return $lines;
    }
}
