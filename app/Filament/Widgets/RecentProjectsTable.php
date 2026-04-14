<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentProjectsTable extends TableWidget
{
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 6,
    ];

    protected ?string $pollingInterval = null;

    protected static ?string $heading = 'Recent Projects';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Project::query()->latest('updated_at')->limit(5))
            ->columns([
                TextColumn::make('name')
                    ->limit(36)
                    ->weight('medium'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'gray',
                        default => 'gray',
                    }),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
            ])
            ->paginated(false)
            ->recordUrl(fn (Project $record): string => ProjectResource::getUrl('edit', ['record' => $record]))
            ->emptyStateHeading('No projects yet.');
    }
}
