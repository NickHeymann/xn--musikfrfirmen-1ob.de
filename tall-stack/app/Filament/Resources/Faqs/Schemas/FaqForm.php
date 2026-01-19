<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('FAQ Content')
                    ->description('Define the frequently asked question and its answer')
                    ->schema([
                        TextInput::make('question')
                            ->label('Question')
                            ->helperText('The question that users frequently ask')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Textarea::make('answer')
                            ->label('Answer')
                            ->helperText('Detailed answer to the question (supports multiple paragraphs)')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),

                        Toggle::make('has_link')
                            ->label('Has Contact Link')
                            ->helperText('Show "Get in touch" link after the answer')
                            ->default(false),
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
