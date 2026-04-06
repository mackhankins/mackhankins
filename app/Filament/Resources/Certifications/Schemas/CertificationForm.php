<?php

namespace App\Filament\Resources\Certifications\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CertificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Certification Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('issuer')
                            ->required(),
                        Forms\Components\TextInput::make('credential_url')
                            ->label('Credential URL')
                            ->url(),
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon name')
                            ->helperText('Simple Icons name, e.g. "anthropic", "aws", "google"'),
                        Forms\Components\DatePicker::make('earned_at')
                            ->label('Date Earned')
                            ->required(),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ])->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
