<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentPostsTable extends TableWidget
{
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 6,
    ];

    protected ?string $pollingInterval = null;

    protected static ?string $heading = 'Recent Posts';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Post::query()->latest('updated_at')->limit(5))
            ->columns([
                TextColumn::make('title')
                    ->limit(44)
                    ->weight('medium'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('updated_at')
                    ->since()
                    ->label('Updated'),
            ])
            ->paginated(false)
            ->recordUrl(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record]))
            ->emptyStateHeading('No posts yet.');
    }
}
