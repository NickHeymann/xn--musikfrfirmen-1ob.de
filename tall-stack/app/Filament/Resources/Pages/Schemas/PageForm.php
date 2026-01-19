<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Page Information')
                    ->description('Define the basic page details')
                    ->schema([
                        TextInput::make('title')
                            ->label('Page Title')
                            ->helperText('The main title of the page (e.g., "Privacy Policy", "Terms of Service")')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->helperText('URL-friendly version of the title (auto-generated)')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('type')
                            ->label('Page Type')
                            ->helperText('Category of the page')
                            ->options([
                                'content' => 'Content Page',
                                'legal' => 'Legal Page',
                                'info' => 'Information Page',
                            ])
                            ->required()
                            ->default('content')
                            ->native(false),
                    ]),

                Section::make('Page Content')
                    ->description('Main content with rich text formatting and image support')
                    ->schema([
                        RichEditor::make('content')
                            ->label('Page Content')
                            ->helperText('Use the toolbar to format text, add images, and create structured content')
                            ->required()
                            ->json() // Store as JSON for flexibility and future API usage
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'h2',
                                'h3',
                                'h4',
                                'bulletList',
                                'orderedList',
                                'link',
                                'blockquote',
                                'codeBlock',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('page-images')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                    ]),

                Section::make('Settings')
                    ->description('Page visibility and ordering settings')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->helperText('Make this page visible on the website')
                            ->default(true)
                            ->inline(false),

                        TextInput::make('display_order')
                            ->label('Display Order')
                            ->helperText('Lower numbers appear first in navigation (0, 1, 2...)')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ])
                    ->columns(2),
            ]);
    }
}
