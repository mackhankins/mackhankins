<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\URL;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn () => URL::temporarySignedRoute(
                    'blog.preview',
                    now()->addHour(),
                    ['post' => $this->record->slug],
                ))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
