<?php

namespace App\Filament\Resources\WorkExperiences\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WorkExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Position Details')
                    ->schema([
                        Forms\Components\TextInput::make('company')
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->required(),
                        Forms\Components\TextInput::make('company_url')
                            ->label('Company URL')
                            ->url(),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Forms\Components\DatePicker::make('start_date')
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date')
                            ->helperText('Leave blank if this is your current position'),
                        Forms\Components\Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
