<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\WritingStudio;
use App\Filament\Resources\PostResource;
use App\Filament\Resources\ProjectResource;
use App\Models\AgentConversation;
use App\Models\Post;
use App\Models\Project;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Draft posts', Post::query()->where('status', 'draft')->count())
                ->description('Ready for review or revision')
                ->descriptionIcon(Heroicon::OutlinedDocumentText)
                ->color('gray')
                ->url(PostResource::getUrl('index')),
            Stat::make('Published posts', Post::query()->where('status', 'published')->count())
                ->description('Live on the site')
                ->descriptionIcon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('success')
                ->url(PostResource::getUrl('index')),
            Stat::make('Projects', Project::query()->count())
                ->description('Draft and published work')
                ->descriptionIcon(Heroicon::OutlinedBolt)
                ->color('warning')
                ->url(ProjectResource::getUrl('index')),
            Stat::make('Writing chats', AgentConversation::query()->count())
                ->description('Saved Codex conversations')
                ->descriptionIcon(Heroicon::OutlinedChatBubbleLeftRight)
                ->color('info')
                ->url(WritingStudio::getUrl()),
        ];
    }
}
