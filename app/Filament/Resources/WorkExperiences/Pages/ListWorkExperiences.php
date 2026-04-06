<?php

namespace App\Filament\Resources\WorkExperiences\Pages;

use App\Filament\Resources\WorkExperiences\WorkExperienceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkExperiences extends ListRecords
{
    protected static string $resource = WorkExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
