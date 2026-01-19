<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->description('Define the team member\'s basic information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->helperText('e.g., "Jonas Müller", "Nick Heymann"')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('role')
                            ->label('Primary Role')
                            ->helperText('Main role or title (e.g., "Gründer")')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('role_second_line')
                            ->label('Secondary Role')
                            ->helperText('Additional role or description (optional)')
                            ->maxLength(255),

                        FileUpload::make('image')
                            ->label('Profile Image')
                            ->helperText('Square image recommended (e.g., 400x400px)')
                            ->image()
                            ->required()
                            ->directory('team-members')
                            ->imageEditor(),
                    ]),

                Section::make('Biography')
                    ->description('Optional detailed biography information')
                    ->schema([
                        TextInput::make('bio_title')
                            ->label('Bio Title')
                            ->helperText('Headline for expanded biography')
                            ->maxLength(255),

                        Textarea::make('bio_text')
                            ->label('Bio Text')
                            ->helperText('Full biography text (supports multiple paragraphs)')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Layout Settings')
                    ->schema([
                        Select::make('position')
                            ->label('Image Position')
                            ->helperText('Position of the profile image in the layout')
                            ->options([
                                'left' => 'Left',
                                'right' => 'Right',
                            ])
                            ->required()
                            ->default('left')
                            ->native(false),

                        TextInput::make('image_class')
                            ->label('Image CSS Class')
                            ->helperText('Custom CSS class for image styling (advanced)')
                            ->maxLength(255),

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
