<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerVehicleResource\Pages;
use App\Filament\Resources\CustomerVehicleResource\RelationManagers;
use App\Models\CustomerVehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerVehicleResource extends Resource
{
    protected static ?string $model = CustomerVehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_id')
                    ->numeric(),
                Forms\Components\TextInput::make('vehicle_manufacturer')
                    ->maxLength(100),
                Forms\Components\TextInput::make('vehicle_name')
                    ->maxLength(100),
                Forms\Components\TextInput::make('vehicle_model_year')
                    ->maxLength(5),
                Forms\Components\TextInput::make('vehicle_color')
                    ->maxLength(50),
                Forms\Components\TextInput::make('vehicle_plate_number')
                    ->maxLength(20),
                Forms\Components\TextInput::make('vehicle_plate_letters_ar')
                    ->maxLength(20),
                Forms\Components\TextInput::make('vehicle_plate_letters_en')
                    ->maxLength(20),
                Forms\Components\Toggle::make('default_vehicle')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle_manufacturer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_model_year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_plate_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_plate_letters_ar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_plate_letters_en')
                    ->searchable(),
                Tables\Columns\IconColumn::make('default_vehicle')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCustomerVehicles::route('/'),
            'create' => Pages\CreateCustomerVehicle::route('/create'),
            'edit' => Pages\EditCustomerVehicle::route('/{record}/edit'),
        ];
    }
}
