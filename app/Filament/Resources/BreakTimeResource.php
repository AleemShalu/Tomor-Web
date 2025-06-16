<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BreakTimeResource\Pages;
use App\Filament\Resources\BreakTimeResource\RelationManagers;
use App\Models\BreakTime;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BreakTimeResource extends Resource
{
    protected static ?string $model = BreakTime::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('work_day_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('break_start_time')
                    ->required(),
                Forms\Components\TextInput::make('break_end_time'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('work_day_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('break_start_time'),
                Tables\Columns\TextColumn::make('break_end_time'),
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
            'index' => Pages\ListBreakTimes::route('/'),
            'create' => Pages\CreateBreakTime::route('/create'),
            'edit' => Pages\EditBreakTime::route('/{record}/edit'),
        ];
    }
}
