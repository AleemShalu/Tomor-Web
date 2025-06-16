<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_id')
                    ->numeric(),
                Forms\Components\TextInput::make('customer_vehicle_id')
                    ->numeric(),
                Forms\Components\TextInput::make('bank_card_id')
                    ->numeric(),
                Forms\Components\TextInput::make('store_id')
                    ->numeric(),
                Forms\Components\TextInput::make('store_branch_id')
                    ->numeric(),
                Forms\Components\TextInput::make('employee_id')
                    ->numeric(),
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('branch_order_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('store_order_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('branch_queue_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('order_date'),
                Forms\Components\TextInput::make('order_color')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_dial_code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_contact_no')
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_vehicle_manufacturer')
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_vehicle_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_vehicle_model_year')
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_vehicle_description')
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_vehicle_color')
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_vehicle_plate_letters')
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_vehicle_plate_number')
                    ->maxLength(255),
                Forms\Components\Toggle::make('customer_special_needs_qualified'),
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
                Forms\Components\TextInput::make('order_currency_code')
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
                Forms\Components\TextInput::make('checkout_method')
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('customer_vehicle_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bank_card_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('store_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('store_branch_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch_order_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('store_order_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('branch_queue_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_dial_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_contact_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_vehicle_manufacturer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_vehicle_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_vehicle_model_year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_vehicle_description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_vehicle_color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_vehicle_plate_letters')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_vehicle_plate_number')
                    ->searchable(),
                Tables\Columns\IconColumn::make('customer_special_needs_qualified')
                    ->boolean(),
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
                Tables\Columns\TextColumn::make('order_currency_code')
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
                Tables\Columns\TextColumn::make('checkout_method')
                    ->searchable(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
