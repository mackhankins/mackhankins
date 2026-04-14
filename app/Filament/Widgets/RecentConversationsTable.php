<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\WritingStudio;
use App\Models\AgentConversation;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentConversationsTable extends TableWidget
{
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 12,
    ];

    protected ?string $pollingInterval = null;

    protected static ?string $heading = 'Recent Writing Chats';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => AgentConversation::query()->withCount('messages')->latest('updated_at')->limit(5))
            ->columns([
                TextColumn::make('title')
                    ->label('Conversation')
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? $state : 'Untitled chat')
                    ->limit(38)
                    ->weight('medium'),
                TextColumn::make('messages_count')
                    ->label('Messages')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('updated_at')
                    ->since()
                    ->label('Updated'),
            ])
            ->paginated(false)
            ->recordUrl(fn (): string => WritingStudio::getUrl())
            ->emptyStateHeading('No writing conversations yet.');
    }
}
