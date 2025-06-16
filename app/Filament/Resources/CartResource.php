<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CartResource\Pages;
use App\Filament\Resources\CartResource\RelationManagers;
use App\Models\Cart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CartResource extends Resource
{
    protected static ?string $model = Cart::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_id')
                    ->numeric(),
                Forms\Components\TextInput::make('items_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('items_quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('exchange_rate')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('conversion_time'),
                Forms\Components\TextInput::make('cart_currency_code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('base_currency_code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('grand_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('base_grand_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('sub_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('base_sub_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('service_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('base_service_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('discount_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('base_discount_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('tax_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('base_tax_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('taxable_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('base_taxable_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('coupon_code')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_gift')
                    ->required(),
                Forms\Components\Toggle::make('is_guest'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('items_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('items_quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('exchange_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('conversion_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cart_currency_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('base_currency_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_grand_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sub_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_sub_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_service_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_discount_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_tax_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('taxable_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_taxable_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('coupon_code')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_gift')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_guest')
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
            'index' => Pages\ListCarts::route('/'),
            'create' => Pages\CreateCart::route('/create'),
            'edit' => Pages\EditCart::route('/{record}/edit'),
        ];
    }
}
