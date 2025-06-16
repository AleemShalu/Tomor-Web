<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusinessResource\Pages;
use App\Filament\Resources\BusinessResource\RelationManagers;
use App\Models\Business;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BusinessResource extends Resource
{
    protected static ?string $model = Business::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_en')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name_ar')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('vat_number')
                    ->maxLength(20),
                Forms\Components\TextInput::make('group_vat_number')
                    ->maxLength(20),
                Forms\Components\TextInput::make('cr_number')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('country_code')
                    ->maxLength(10),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(30),
                Forms\Components\TextInput::make('country')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('state')
                    ->maxLength(50),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('district')
                    ->maxLength(50),
                Forms\Components\TextInput::make('street')
                    ->maxLength(100),
                Forms\Components\TextInput::make('building_no')
                    ->maxLength(20),
                Forms\Components\TextInput::make('zipcode')
                    ->maxLength(15),
                Forms\Components\TextInput::make('logo')
                    ->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_ar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vat_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('group_vat_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cr_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('district')
                    ->searchable(),
                Tables\Columns\TextColumn::make('street')
                    ->searchable(),
                Tables\Columns\TextColumn::make('building_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('zipcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('logo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
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
            'index' => Pages\ListBusinesses::route('/'),
            'create' => Pages\CreateBusiness::route('/create'),
            'edit' => Pages\EditBusiness::route('/{record}/edit'),
        ];
    }
}
