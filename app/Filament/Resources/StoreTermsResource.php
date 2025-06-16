<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreTermsResource\Pages;
use App\Filament\Resources\StoreTermsResource\RelationManagers;
use App\Models\StoreTerms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreTermsResource extends Resource
{
    protected static ?string $model = StoreTerms::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('body_ar')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('body_en')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('issued_at'),
                Forms\Components\DateTimePicker::make('expired_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('issued_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expired_at')
                    ->dateTime()
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
            'index' => Pages\ListStoreTerms::route('/'),
            'create' => Pages\CreateStoreTerms::route('/create'),
            'edit' => Pages\EditStoreTerms::route('/{record}/edit'),
        ];
    }
}
