<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderItemResource\Pages;
use App\Filament\Resources\OrderItemResource\RelationManagers;
use App\Models\OrderItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderItemResource extends Resource
{
    protected static ?string $model = OrderItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->numeric(),
                Forms\Components\TextInput::make('product_id')
                    ->numeric(),
                Forms\Components\TextInput::make('item_code')
                    ->maxLength(50),
                Forms\Components\TextInput::make('item_name')
                    ->maxLength(255),
                Forms\Components\Textarea::make('item_description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('item_unit_price')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('item_status')
                    ->maxLength(255),
                Forms\Components\TextInput::make('item_total')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('note')
                    ->maxLength(255),
                Forms\Components\TextInput::make('voice_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('voice_path')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_id')
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
                Tables\Columns\TextColumn::make('item_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->searchable(),
                Tables\Columns\TextColumn::make('voice_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('voice_path')
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
            'index' => Pages\ListOrderItems::route('/'),
            'create' => Pages\CreateOrderItem::route('/create'),
            'edit' => Pages\EditOrderItem::route('/{record}/edit'),
        ];
    }
}
