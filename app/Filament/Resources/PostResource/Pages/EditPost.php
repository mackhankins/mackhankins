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
                ->label(fn () => $this->record->isPublished() ? 'View' : 'Preview')
                ->icon(fn () => $this->record->isPublished() ? 'heroicon-o-arrow-top-right-on-square' : 'heroicon-o-eye')
                ->color('gray')
                ->url(fn () => $this->record->isPublished()
                    ? route('blog.show', $this->record)
                    : URL::temporarySignedRoute('blog.preview', now()->addHour(), ['post' => $this->record->slug])
                )
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
