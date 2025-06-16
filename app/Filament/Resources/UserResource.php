<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('store_id')
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('username')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('dial_code')
                    ->maxLength(10),
                Forms\Components\TextInput::make('contact_no')
                    ->maxLength(30),
                Forms\Components\DateTimePicker::make('phone_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255),
                Forms\Components\Textarea::make('two_factor_secret')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('two_factor_recovery_codes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('status')
                    ->required(),
                Forms\Components\TextInput::make('user_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('auth_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('auth_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('robot_auth')
                    ->maxLength(255),
                Forms\Components\TextInput::make('profile_photo_path')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('last_seen'),
                Forms\Components\TextInput::make('theme')
                    ->maxLength(255)
                    ->default('default'),
                Forms\Components\TextInput::make('theme_color')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('store_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dial_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('user_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('auth_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('auth_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('robot_auth')
                    ->searchable(),
                Tables\Columns\TextColumn::make('profile_photo_path')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_seen')
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
                Tables\Columns\TextColumn::make('theme')
                    ->searchable(),
                Tables\Columns\TextColumn::make('theme_color')
                    ->searchable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
