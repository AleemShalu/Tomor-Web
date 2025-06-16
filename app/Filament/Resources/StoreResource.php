<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Filament\Resources\StoreResource\RelationManagers;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('business_type_id')
                    ->relationship('business_type', 'id'),
                Forms\Components\Select::make('country_id')
                    ->relationship('country', 'id'),
                Forms\Components\Select::make('owner_id')
                    ->relationship('owner', 'name'),
                Forms\Components\TextInput::make('commercial_name_en')
                    ->maxLength(255),
                Forms\Components\TextInput::make('commercial_name_ar')
                    ->maxLength(255),
                Forms\Components\TextInput::make('short_name_en')
                    ->maxLength(255),
                Forms\Components\TextInput::make('short_name_ar')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description_ar')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description_en')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('dial_code')
                    ->maxLength(10),
                Forms\Components\TextInput::make('contact_no')
                    ->maxLength(30),
                Forms\Components\TextInput::make('secondary_dial_code')
                    ->maxLength(10),
                Forms\Components\TextInput::make('secondary_contact_no')
                    ->maxLength(30),
                Forms\Components\TextInput::make('tax_id_number')
                    ->maxLength(50),
                Forms\Components\TextInput::make('tax_id_attachment')
                    ->maxLength(255),
                Forms\Components\TextInput::make('commercial_registration_no')
                    ->maxLength(50),
                Forms\Components\DatePicker::make('commercial_registration_expiry'),
                Forms\Components\TextInput::make('commercial_registration_attachment')
                    ->maxLength(255),
                Forms\Components\TextInput::make('municipal_license_no')
                    ->maxLength(50),
                Forms\Components\TextInput::make('api_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('api_admin_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('menu_pdf')
                    ->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->maxLength(255),
                Forms\Components\TextInput::make('logo')
                    ->maxLength(255),
                Forms\Components\TextInput::make('store_header')
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')
                    ->required(),
                Forms\Components\TextInput::make('range_time_order')
                    ->required()
                    ->numeric()
                    ->default(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business_type.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('commercial_name_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('commercial_name_ar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('short_name_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('short_name_ar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dial_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('secondary_dial_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('secondary_contact_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tax_id_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tax_id_attachment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('commercial_registration_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('commercial_registration_expiry')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('commercial_registration_attachment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('municipal_license_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('api_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('api_admin_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('menu_pdf')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->searchable(),
                Tables\Columns\TextColumn::make('logo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('store_header')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('range_time_order')
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
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
