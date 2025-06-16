<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialNeedsTypeResource\Pages;
use App\Filament\Resources\SpecialNeedsTypeResource\RelationManagers;
use App\Models\SpecialNeedsType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpecialNeedsTypeResource extends Resource
{
    protected static ?string $model = SpecialNeedsType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('disability_type_en')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('disability_type_ar')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('disability_type_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('disability_type_ar')
                    ->searchable(),
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
            'index' => Pages\ListSpecialNeedsTypes::route('/'),
            'create' => Pages\CreateSpecialNeedsType::route('/create'),
            'edit' => Pages\EditSpecialNeedsType::route('/{record}/edit'),
        ];
    }
}
