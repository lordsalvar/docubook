<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Organization;
use App\Enums\Designation;
use App\Enums\StatusEnum;
use Filament\Forms\Components\Select;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('avatar')
                    ->avatar()
                    ->imageEditor()
                    ->columnSpanFull()
                    ->extraAttributes([
                        'class' => 'mx-auto',
                    ])
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ]),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('password')
                    ->required(fn (string $context): bool => $context === 'create')
                    ->password(),
                TextInput::make('password_confirmation')
                    ->required(fn (string $context): bool => $context === 'create')
                    ->password(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('role'),
                Tables\Columns\TextColumn::make('contact'),   
                Tables\Columns\TextColumn::make('organizations.name'),
                Tables\Columns\TextColumn::make('memberships.organization.name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('created_at'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('addMembership')
                ->label('Add Membership')
                ->icon('heroicon-o-plus')
                ->form([
                    Select::make('organization_id')
                    ->label('Organization')
                    ->options(fn () => Organization::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload()
                    ->required(),
                    Select::make('designation')
                    ->label('Role')
                    ->options(Designation::class)
                    ->required(),
                    Select::make('status')
                    ->label('Status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive'])
                    ->default('active')
                    ->required(),
                ])
                ->action(function (array $data, User $record) {
                    $record->memberships()->create([
                        'organization_id' => $data['organization_id'],
                        'designation' => $data['designation'],
                        'status' => $data['status'],
                        'joined_date' => now(),
                    ]);
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
