<?php

namespace App\Filament\Resources\FacilityResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'rooms';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('room_number')
                    ->label('Room Number')
                    ->numeric()
                    ->required()
                    ->maxLength(255)
                    ->rules([
                        'unique:rooms,room_number,NULL,id,facility_id,'.$this->ownerRecord->id,
                    ])
                    ->validationMessages([
                        'unique' => 'A room with this number already exists for this facility.',
                    ]),
                Forms\Components\TextInput::make('capacity')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('room_number')
            ->columns([
                Tables\Columns\TextColumn::make('room_number'),
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('capacity'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data) {
                        $facilityCode = $this->ownerRecord->code ?? '';
                        $data['code'] = $facilityCode.'-'.$data['room_number'];
                        $data['facility_id'] = $this->ownerRecord->id;

                        return $this->ownerRecord->rooms()->create($data);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->using(function (array $data, $record) {
                        $facilityCode = $this->ownerRecord->code ?? '';
                        $data['code'] = $facilityCode.'-'.$data['room_number'];

                        $record->update($data);

                        return $record;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
}
