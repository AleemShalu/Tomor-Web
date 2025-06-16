<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OtpPhoneVerificationResource\Pages;
use App\Filament\Resources\OtpPhoneVerificationResource\RelationManagers;
use App\Models\OtpPhoneVerification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OtpPhoneVerificationResource extends Resource
{
    protected static ?string $model = OtpPhoneVerification::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('dial_code')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('contact_no')
                    ->required()
                    ->maxLength(30),
                Forms\Components\TextInput::make('code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('attempts')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(20),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dial_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('attempts')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
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
            'index' => Pages\ListOtpPhoneVerifications::route('/'),
            'create' => Pages\CreateOtpPhoneVerification::route('/create'),
            'edit' => Pages\EditOtpPhoneVerification::route('/{record}/edit'),
        ];
    }
}
