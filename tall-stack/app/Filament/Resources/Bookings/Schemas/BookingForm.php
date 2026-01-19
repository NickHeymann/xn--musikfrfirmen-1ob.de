<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event Selection')
                    ->description('Select the event for this booking')
                    ->schema([
                        Select::make('event_id')
                            ->label('Event')
                            ->helperText('Select the event being booked')
                            ->relationship('event', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false),
                    ]),

                Section::make('Company Information')
                    ->description('Details about the company or organization making the booking')
                    ->schema([

                        TextInput::make('company_name')
                            ->label('Company Name')
                            ->helperText('Name of the company or organization')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('contact_person')
                            ->label('Contact Person')
                            ->helperText('Primary contact for this booking')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->helperText('Email for booking confirmations')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->helperText('Contact phone number')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Booking Details')
                    ->description('Pricing, musicians, and special requirements')
                    ->schema([

                        TextInput::make('num_musicians')
                            ->label('Number of Musicians')
                            ->helperText('How many musicians are needed for this booking')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->suffix('musicians'),

                        TextInput::make('total_price')
                            ->label('Total Price')
                            ->helperText('Calculated total price for the booking')
                            ->required()
                            ->numeric()
                            ->prefix('€')
                            ->minValue(0)
                            ->step(0.01)
                            ->disabled()
                            ->dehydrated(),

                        Select::make('status')
                            ->label('Booking Status')
                            ->helperText('Current status of this booking')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'paid' => 'Paid',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->native(false),

                        Textarea::make('special_requests')
                            ->label('Special Requests')
                            ->helperText('Any special requirements or requests for this booking')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
