<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminStatsOverview;
use App\Filament\Widgets\RecentConversationsTable;
use App\Filament\Widgets\RecentPostsTable;
use App\Filament\Widgets\RecentProjectsTable;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\WidgetConfiguration;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 12,
        ];
    }

    /**
     * @return array<class-string|WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            AdminStatsOverview::class,
            RecentPostsTable::class,
            RecentProjectsTable::class,
            RecentConversationsTable::class,
        ];
    }
}
