<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->numeric(),
                Forms\Components\TextInput::make('invoice_type_id')
                    ->numeric(),
                Forms\Components\TextInput::make('business_id')
                    ->numeric(),
                Forms\Components\TextInput::make('store_id')
                    ->numeric(),
                Forms\Components\TextInput::make('store_branch_id')
                    ->numeric(),
                Forms\Components\TextInput::make('customer_id')
                    ->numeric(),
                Forms\Components\TextInput::make('issued_by')
                    ->numeric(),
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('invoice_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->maxLength(255),
                Forms\Components\TextInput::make('invoice_locale')
                    ->maxLength(5),
                Forms\Components\DateTimePicker::make('invoice_date'),
                Forms\Components\DateTimePicker::make('supply_date'),
                Forms\Components\TextInput::make('business_name_ar')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('business_name_en')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('business_address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('business_vat_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('business_group_vat_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('business_cr_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('store_invoice_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('store_name_ar')
                    ->maxLength(255),
                Forms\Components\TextInput::make('store_name_en')
                    ->maxLength(255),
                Forms\Components\TextInput::make('store_branch_invoice_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('store_branch_name_ar')
                    ->maxLength(255),
                Forms\Components\TextInput::make('store_branch_name_en')
                    ->maxLength(255),
                Forms\Components\TextInput::make('store_branch_address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_dial_code')
                    ->maxLength(10),
                Forms\Components\TextInput::make('customer_contact_no')
                    ->maxLength(30),
                Forms\Components\TextInput::make('invoice_discount_percentage')
                    ->numeric(),
                Forms\Components\TextInput::make('invoice_discount_amount')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('total_discount')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('total_taxtable_amount')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('total_vat')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('exchange_rate')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('conversion_time'),
                Forms\Components\TextInput::make('total_vat_in_sar')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\TextInput::make('gross_total_including_vat')
                    ->numeric()
                    ->default(0.0000),
                Forms\Components\Textarea::make('path')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('qrcode_path')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('invoice_hash')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('additional')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('business_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('store_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('store_branch_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('issued_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_locale')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supply_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('business_name_ar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_name_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_vat_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_group_vat_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_cr_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('store_invoice_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('store_name_ar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('store_name_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('store_branch_invoice_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('store_branch_name_ar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('store_branch_name_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('store_branch_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_dial_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_contact_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_discount_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_discount_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_discount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_taxtable_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_vat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('exchange_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('conversion_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_vat_in_sar')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gross_total_including_vat')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
