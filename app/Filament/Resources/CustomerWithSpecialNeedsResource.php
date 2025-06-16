<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerWithSpecialNeedsResource\Pages;
use App\Filament\Resources\CustomerWithSpecialNeedsResource\RelationManagers;
use App\Models\CustomerWithSpecialNeeds;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerWithSpecialNeedsResource extends Resource
{
    protected static ?string $model = CustomerWithSpecialNeeds::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_id')
                    ->numeric(),
                Forms\Components\TextInput::make('special_needs_type_id')
                    ->numeric(),
                Forms\Components\Toggle::make('special_needs_qualified'),
                Forms\Components\TextInput::make('special_needs_sa_card_number')
                    ->maxLength(20),
                Forms\Components\TextInput::make('special_needs_description')
                    ->maxLength(255),
                Forms\Components\TextInput::make('special_needs_attachment')
                    ->maxLength(255),
                Forms\Components\TextInput::make('special_needs_status')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('special_needs_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('special_needs_qualified')
                    ->boolean(),
                Tables\Columns\TextColumn::make('special_needs_sa_card_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('special_needs_description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('special_needs_attachment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('special_needs_status')
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
            'index' => Pages\ListCustomerWithSpecialNeeds::route('/'),
            'create' => Pages\CreateCustomerWithSpecialNeeds::route('/create'),
            'edit' => Pages\EditCustomerWithSpecialNeeds::route('/{record}/edit'),
        ];
    }
}
