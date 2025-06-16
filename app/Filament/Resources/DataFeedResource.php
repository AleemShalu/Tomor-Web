<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataFeedResource\Pages;
use App\Filament\Resources\DataFeedResource\RelationManagers;
use App\Models\DataFeed;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataFeedResource extends Resource
{
    protected static ?string $model = DataFeed::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->maxLength(255),
                Forms\Components\TextInput::make('data')
                    ->numeric(),
                Forms\Components\Toggle::make('dataset_name'),
                Forms\Components\Toggle::make('data_type')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('dataset_name')
                    ->boolean(),
                Tables\Columns\IconColumn::make('data_type')
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
            'index' => Pages\ListDataFeeds::route('/'),
            'create' => Pages\CreateDataFeed::route('/create'),
            'edit' => Pages\EditDataFeed::route('/{record}/edit'),
        ];
    }
}
