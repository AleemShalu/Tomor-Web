<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceItemResource\Pages;
use App\Filament\Resources\InvoiceItemResource\RelationManagers;
use App\Models\InvoiceItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceItemResource extends Resource
{
    protected static ?string $model = InvoiceItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('invoice_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('item_supply_date'),
                Forms\Components\TextInput::make('item_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('item_sku')
                    ->maxLength(255),
                Forms\Components\TextInput::make('item_model_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('item_barcode')
                    ->maxLength(255),
                Forms\Components\TextInput::make('item_code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('item_name')
                    ->maxLength(255),
                Forms\Components\Textarea::make('item_description')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('item_unit')
                    ->maxLength(255),
                Forms\Components\TextInput::make('unit_price')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_unit_discount')
                    ->numeric(),
                Forms\Components\TextInput::make('item_discount_percentage')
                    ->numeric(),
                Forms\Components\TextInput::make('invoice_level_discount_percentage')
                    ->numeric(),
                Forms\Components\TextInput::make('item_discount_amount')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('taxable_subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('vat_code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('vat_code_description')
                    ->maxLength(255),
                Forms\Components\TextInput::make('vat_rate')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('vat_amount')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('subtotal_including_vat')
                    ->required()
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
                Tables\Columns\TextColumn::make('invoice_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_supply_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_sku')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_model_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_barcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_unit_discount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_discount_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_level_discount_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_discount_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('taxable_subtotal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vat_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vat_code_description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vat_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vat_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal_including_vat')
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
            'index' => Pages\ListInvoiceItems::route('/'),
            'create' => Pages\CreateInvoiceItem::route('/create'),
            'edit' => Pages\EditInvoiceItem::route('/{record}/edit'),
        ];
    }
}
