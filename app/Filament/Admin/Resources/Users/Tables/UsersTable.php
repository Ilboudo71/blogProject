<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\ImageColumn;


class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable(),
                ImageColumn::make('photo')
                ->imageHeight(40)
                ->circular()->disk('public'),
                TextColumn::make('name')->label('Name')->searchable()->sortable(),
                TextColumn::make('first_name')->label('First Name')->searchable()->sortable(),
                TextColumn::make('number_phone')->label('Number Phone')->searchable()->sortable(),
                TextColumn::make('email')->label('Email')->searchable()->sortable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->filters([
            SelectFilter::make('year')
                ->label('Année')
                ->options([
                    '2024' => '2024',
                    '2025' => '2025',
                    '2026' => '2026',
                    '2027' => '2027',
                ])
                ->query(function (Builder $query, array $data): Builder {
                    if (blank($data['value'])) {
                        return $query;
                    }

                    return $query->whereYear('created_at', $data['value']);
                }),

                SelectFilter::make('month')
                    ->label('Mois')
                    ->options([
                        '1'  => 'Janvier',
                        '2'  => 'Février',
                        '3'  => 'Mars',
                        '4'  => 'Avril',
                        '5'  => 'Mai',
                        '6'  => 'Juin',
                        '7'  => 'Juillet',
                        '8'  => 'Août',
                        '9'  => 'Septembre',
                        '10' => 'Octobre',
                        '11' => 'Novembre',
                        '12' => 'Décembre',
                    ])
        ->query(function (Builder $query, array $data): Builder {
            if (blank($data['value'])) {
                return $query;
            }

            return $query->whereMonth('created_at', $data['value']);
        }),
        ]);
    }
}
