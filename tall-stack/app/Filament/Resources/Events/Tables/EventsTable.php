<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('location')
                    ->searchable(),

                TextColumn::make('start_time')
                    ->label('Start Date')
                    ->dateTime('M d, Y - H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'booked' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),

                TextColumn::make('music_style')
                    ->badge()
                    ->color('purple')
                    ->searchable(),

                TextColumn::make('price_per_musician')
                    ->label('Price')
                    ->money('EUR')
                    ->sortable(),

                TextColumn::make('musicians_needed')
                    ->label('Musicians')
                    ->numeric()
                    ->suffix(' needed')
                    ->sortable(),

                TextColumn::make('bookings_count')
                    ->counts('bookings')
                    ->label('Bookings')
                    ->badge()
                    ->color('info'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'booked' => 'Booked',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('music_style')
                    ->options(function () {
                        return \App\Models\Event::query()
                            ->whereNotNull('music_style')
                            ->distinct()
                            ->pluck('music_style', 'music_style')
                            ->toArray();
                    }),
            ])
            ->defaultSort('start_time', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
