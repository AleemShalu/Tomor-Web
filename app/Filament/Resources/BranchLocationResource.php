<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchLocationResource\Pages;
use App\Filament\Resources\BranchLocationResource\RelationManagers;
use App\Models\BranchLocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BranchLocationResource extends Resource
{
    protected static ?string $model = BranchLocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('store_branch_id')
                    ->numeric(),
                Forms\Components\TextInput::make('location_description')
                    ->maxLength(255),
                Forms\Components\TextInput::make('location_radius')
                    ->maxLength(255),
                Forms\Components\TextInput::make('latitude')
                    ->maxLength(255),
                Forms\Components\TextInput::make('longitude')
                    ->maxLength(255),
                Forms\Components\TextInput::make('district')
                    ->maxLength(255),
                Forms\Components\TextInput::make('national_address')
                    ->maxLength(50),
                Forms\Components\TextInput::make('google_maps_url')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('store_branch_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location_description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location_radius')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->searchable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->searchable(),
                Tables\Columns\TextColumn::make('district')
                    ->searchable(),
                Tables\Columns\TextColumn::make('national_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('google_maps_url')
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
            'index' => Pages\ListBranchLocations::route('/'),
            'create' => Pages\CreateBranchLocation::route('/create'),
            'edit' => Pages\EditBranchLocation::route('/{record}/edit'),
        ];
    }
}
