<?php

namespace App\Filament\Resources\MembershipResource\RelationManagers;

use App\Enums\Designation;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;

class UserRelationManager extends RelationManager
{
    protected static string $relationship = 'memberships';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('designation')
                    ->options(Designation::class)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Members')
            ->defaultSort(function ($query) {
                return $query
                    ->orderByRaw("CASE 
                        WHEN designation = 'Moderator' THEN 1
                        WHEN designation = 'Dean' THEN 2
                        ELSE 3 
                    END")
                    ->orderBy('user.name', 'asc');
            })
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name'),
                TextColumn::make('designation'),
                TextColumn::make('joined_date'),
            ])
            ->filters([
                // TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Member')
                    ->slideOver(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
