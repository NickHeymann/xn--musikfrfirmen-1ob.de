<?php

namespace App\Filament\Resources\ContactSubmissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact Information')
                    ->description('Details about the person submitting the inquiry')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->helperText('Contact person\'s full name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->helperText('Primary email for correspondence')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->helperText('Optional phone number for follow-up')
                            ->tel()
                            ->maxLength(255),

                        TextInput::make('company')
                            ->label('Company Name')
                            ->helperText('Optional company or organization name')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Inquiry Details')
                    ->description('Information about the inquiry and its current status')
                    ->schema([

                        Select::make('inquiry_type')
                            ->label('Inquiry Type')
                            ->helperText('Categorize the inquiry for proper routing')
                            ->required()
                            ->options([
                                'general' => 'General Inquiry',
                                'booking' => 'Event Booking',
                                'partnership' => 'Partnership Opportunity',
                                'other' => 'Other',
                            ])
                            ->default('general')
                            ->native(false),

                        Textarea::make('message')
                            ->label('Message')
                            ->helperText('The inquiry message from the contact')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Select::make('status')
                            ->label('Status')
                            ->helperText('Track the progress of this inquiry')
                            ->required()
                            ->options([
                                'new' => 'New',
                                'contacted' => 'Contacted',
                                'converted' => 'Converted',
                                'archived' => 'Archived',
                            ])
                            ->default('new')
                            ->native(false),
                    ]),
            ]);
    }
}
