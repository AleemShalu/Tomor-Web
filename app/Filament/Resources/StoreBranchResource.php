<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreBranchResource\Pages;
use App\Filament\Resources\StoreBranchResource\RelationManagers;
use App\Models\StoreBranch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreBranchResource extends Resource
{
    protected static ?string $model = StoreBranch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('bank_account_id')
                    ->numeric(),
                Forms\Components\TextInput::make('city_id')
                    ->numeric(),
                Forms\Components\TextInput::make('store_id')
                    ->numeric(),
                Forms\Components\TextInput::make('name_ar')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name_en')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('branch_serial_number')
                    ->maxLength(50),
                Forms\Components\TextInput::make('qr_code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('commercial_registration_no')
                    ->maxLength(50),
                Forms\Components\DatePicker::make('commercial_registration_expiry'),
                Forms\Components\TextInput::make('commercial_registration_attachment')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('dial_code')
                    ->maxLength(10),
                Forms\Components\TextInput::make('contact_no')
                    ->maxLength(30),
                Forms\Components\Toggle::make('default_branch')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bank_account_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('store_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name_ar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('branch_serial_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qr_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('commercial_registration_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('commercial_registration_expiry')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('commercial_registration_attachment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dial_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_no')
                    ->searchable(),
                Tables\Columns\IconColumn::make('default_branch')
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
            'index' => Pages\ListStoreBranches::route('/'),
            'create' => Pages\CreateStoreBranch::route('/create'),
            'edit' => Pages\EditStoreBranch::route('/{record}/edit'),
        ];
    }
}
