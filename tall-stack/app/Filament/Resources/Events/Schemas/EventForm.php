<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event Information')
                    ->description('Basic details about the event')
                    ->schema([
                        TextInput::make('title')
                            ->label('Event Title')
                            ->helperText('Descriptive name for the event')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Description')
                            ->helperText('Full description of the event and its requirements')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('location')
                            ->label('Location')
                            ->helperText('Event venue or address')
                            ->required()
                            ->maxLength(255),

                        DateTimePicker::make('start_time')
                            ->label('Start Time')
                            ->helperText('When the event begins')
                            ->required()
                            ->native(false),

                        DateTimePicker::make('end_time')
                            ->label('End Time')
                            ->helperText('When the event ends')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Section::make('Capacity & Pricing')
                    ->description('Event capacity and musician pricing details')
                    ->schema([

                        TextInput::make('capacity')
                            ->label('Event Capacity')
                            ->helperText('Maximum number of attendees')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->suffix('people'),

                        TextInput::make('price_per_musician')
                            ->label('Price Per Musician')
                            ->helperText('Payment per musician for this event')
                            ->required()
                            ->numeric()
                            ->prefix('€')
                            ->minValue(0)
                            ->step(0.01),

                        TextInput::make('musicians_needed')
                            ->label('Musicians Needed')
                            ->helperText('Number of musicians required')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->suffix('musicians'),

                        TextInput::make('music_style')
                            ->label('Music Style')
                            ->helperText('Genre or style of music needed (e.g., Jazz, Classical, Rock)')
                            ->maxLength(255)
                            ->placeholder('Jazz, Classical, Rock...'),
                    ])
                    ->columns(2),

                Section::make('Status & Requirements')
                    ->description('Event status and additional requirements')
                    ->schema([

                        Select::make('status')
                            ->label('Event Status')
                            ->helperText('Current status of this event')
                            ->required()
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'booked' => 'Booked',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft')
                            ->native(false),

                        KeyValue::make('requirements')
                            ->label('Requirements')
                            ->helperText('Equipment, dress code, or other specific requirements (key-value pairs)')
                            ->keyLabel('Requirement Type')
                            ->valueLabel('Details')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
