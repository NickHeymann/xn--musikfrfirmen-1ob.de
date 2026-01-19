<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service Details')
                    ->description('Define the service step information')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->helperText('e.g., "60 Sekunden", "24 Stunden"')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('text')
                            ->label('Text (before highlight)')
                            ->helperText('The text that appears before the highlighted portion')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('highlight')
                            ->label('Highlighted Text')
                            ->helperText('The text that will be highlighted in turquoise')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Description (after highlight)')
                            ->helperText('The text that appears after the highlighted portion')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Settings')
                    ->schema([
                        TextInput::make('display_order')
                            ->label('Display Order')
                            ->helperText('Lower numbers appear first (0, 1, 2...)')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->required()
                            ->default('active')
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }
}
