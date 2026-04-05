<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
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

        $this->drawGridPattern($canvas);

        $contentRightBound = self::WIDTH - 80;

        // Decorative amber accent line at top
        $canvas->drawRectangle(function ($draw) {
            $draw->size(60, 3);
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

    private function drawGridPattern(ImageInterface $canvas): void
    {
        $gridSize = 40;
        $color = 'rgba(53, 60, 82, 0.12)';

        // Full-background vertical lines
        for ($x = 0; $x <= self::WIDTH; $x += $gridSize) {
            $canvas->drawLine(function ($draw) use ($x, $color) {
                $draw->from($x, 0);
                $draw->to($x, self::HEIGHT);
                $draw->color($color);
                $draw->width(1);
            });
        }

        // Full-background horizontal lines
        for ($y = 0; $y <= self::HEIGHT; $y += $gridSize) {
            $canvas->drawLine(function ($draw) use ($y, $color) {
                $draw->from(0, $y);
                $draw->to(self::WIDTH, $y);
                $draw->color($color);
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
        $fontSize = 15;
        $x = 80;

        $label = implode('  /  ', $tags);

        $canvas->text($label, $x, $y, function (FontFactory $font) use ($fontPath, $fontSize) {
            $font->filename($fontPath);
            $font->size($fontSize);
            $font->color('#c9a872');
            $font->align('left', 'top');
        });
    }

    private function drawBranding(ImageInterface $canvas): void
    {
        // Bottom bar background
        $canvas->drawRectangle(function ($draw) {
            $draw->size(self::WIDTH, 70);
            $draw->at(0, self::HEIGHT - 70);
            $draw->background('rgba(10, 12, 18, 0.85)');
        });

        // Top border — gradient-style using a faint amber line
        $canvas->drawLine(function ($draw) {
            $draw->from(80, self::HEIGHT - 70);
            $draw->to(self::WIDTH - 80, self::HEIGHT - 70);
            $draw->color('rgba(201, 168, 114, 0.15)');
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
