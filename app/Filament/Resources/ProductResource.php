<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_brand_id')
                    ->numeric(),
                Forms\Components\TextInput::make('product_category_id')
                    ->numeric(),
                Forms\Components\TextInput::make('store_id')
                    ->numeric(),
                Forms\Components\TextInput::make('product_code')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('model_number')
                    ->maxLength(50),
                Forms\Components\TextInput::make('barcode')
                    ->maxLength(100),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('availability')
                    ->maxLength(255),
                Forms\Components\TextInput::make('unit_price')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('calories')
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_brand_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('store_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('barcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('availability')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('calories')
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
