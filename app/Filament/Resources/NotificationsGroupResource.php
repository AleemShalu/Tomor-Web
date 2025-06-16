<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationsGroupResource\Pages;
use App\Filament\Resources\NotificationsGroupResource\RelationManagers;
use App\Models\NotificationsGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotificationsGroupResource extends Resource
{
    protected static ?string $model = NotificationsGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('notification_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('platform_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('users_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('notification_title_ar')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('notification_message_ar')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('notification_title_en')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('notification_message_en')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('notification_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('platform_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notification_title_ar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notification_title_en')
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
            'index' => Pages\ListNotificationsGroups::route('/'),
            'create' => Pages\CreateNotificationsGroup::route('/create'),
            'edit' => Pages\EditNotificationsGroup::route('/{record}/edit'),
        ];
    }
}
