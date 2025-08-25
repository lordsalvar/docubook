<?php

namespace App\Filament\Resources\MembershipResource\RelationManagers;

use App\Enums\Designation;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

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
                Forms\Components\DatePicker::make('joined_date')
                    ->default(now())
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('membership')
            ->columns([
                Tables\Columns\TextColumn::make('membership'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Member'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
