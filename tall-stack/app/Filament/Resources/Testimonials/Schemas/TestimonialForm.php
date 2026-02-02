<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Testimonial Content')
                    ->schema([
                        Textarea::make('quote')
                            ->label('Quote')
                            ->required()
                            ->rows(4)
                            ->maxLength(1000)
                            ->columnSpanFull()
                            ->helperText('Das Zitat des Kunden'),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('author_name')
                                    ->label('Author Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('z.B. Peter Weber'),

                                TextInput::make('author_position')
                                    ->label('Position')
                                    ->maxLength(255)
                                    ->helperText('z.B. CEO'),

                                TextInput::make('author_company')
                                    ->label('Company')
                                    ->maxLength(255)
                                    ->helperText('z.B. Let Me Ship GmbH'),

                                FileUpload::make('author_image')
                                    ->label('Author Image')
                                    ->image()
                                    ->directory('testimonials')
                                    ->maxSize(2048)
                                    ->helperText('Optional: Foto des Autors'),
                            ]),
                    ]),

                Section::make('Display Settings')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->helperText('Wird auf der Hauptseite angezeigt')
                                    ->default(false),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->helperText('Testimonial ist sichtbar')
                                    ->default(true),

                                TextInput::make('display_order')
                                    ->label('Display Order')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Niedrigere Zahlen = höhere Priorität'),
                            ]),
                    ]),
            ]);
    }
}
