<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreTermsApprovedByStoreResource\Pages;
use App\Filament\Resources\StoreTermsApprovedByStoreResource\RelationManagers;
use App\Models\StoreTermsApprovedByStore;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreTermsApprovedByStoreResource extends Resource
{
    protected static ?string $model = StoreTermsApprovedByStore::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('store_terms_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('store_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('approved')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('store_terms_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('store_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('approved')
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
            'index' => Pages\ListStoreTermsApprovedByStores::route('/'),
            'create' => Pages\CreateStoreTermsApprovedByStore::route('/create'),
            'edit' => Pages\EditStoreTermsApprovedByStore::route('/{record}/edit'),
        ];
    }
}
