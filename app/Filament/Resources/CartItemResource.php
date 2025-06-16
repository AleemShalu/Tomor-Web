<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CartItemResource\Pages;
use App\Filament\Resources\CartItemResource\RelationManagers;
use App\Models\CartItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CartItemResource extends Resource
{
    protected static ?string $model = CartItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cart_id')
                    ->numeric(),
                Forms\Components\TextInput::make('product_id')
                    ->numeric(),
                Forms\Components\TextInput::make('tax_code_id')
                    ->numeric(),
                Forms\Components\TextInput::make('item_code')
                    ->maxLength(50),
                Forms\Components\TextInput::make('item_name')
                    ->maxLength(255),
                Forms\Components\Textarea::make('item_description')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('item_unit_price')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_base_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_amount')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_base_amount')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_discount_rate')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_discount_amount')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_coupon_code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('item_tax_rate')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_tax_amount')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_base_tax_amount')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_taxable_amount')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_base_taxable_amount')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('remarks')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cart_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_code_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_unit_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_base_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_base_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_discount_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_discount_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_coupon_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_tax_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_tax_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_base_tax_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_taxable_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_base_taxable_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remarks')
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
            'index' => Pages\ListCartItems::route('/'),
            'create' => Pages\CreateCartItem::route('/create'),
            'edit' => Pages\EditCartItem::route('/{record}/edit'),
        ];
    }
}
