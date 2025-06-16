<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankCardResource\Pages;
use App\Filament\Resources\BankCardResource\RelationManagers;
use App\Models\BankCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankCardResource extends Resource
{
    protected static ?string $model = BankCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_id')
                    ->numeric(),
                Forms\Components\TextInput::make('card_holder_name')
                    ->maxLength(100),
                Forms\Components\TextInput::make('card_number')
                    ->maxLength(50),
                Forms\Components\TextInput::make('card_expiry_year')
                    ->maxLength(5),
                Forms\Components\TextInput::make('card_expiry_month')
                    ->maxLength(5),
                Forms\Components\TextInput::make('card_cvv')
                    ->maxLength(5),
                Forms\Components\Toggle::make('default_card')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('card_holder_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('card_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('card_expiry_year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('card_expiry_month')
                    ->searchable(),
                Tables\Columns\TextColumn::make('card_cvv')
                    ->searchable(),
                Tables\Columns\IconColumn::make('default_card')
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
            'index' => Pages\ListBankCards::route('/'),
            'create' => Pages\CreateBankCard::route('/create'),
            'edit' => Pages\EditBankCard::route('/{record}/edit'),
        ];
    }
}
