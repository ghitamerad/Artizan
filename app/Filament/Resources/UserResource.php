<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack'; // Icône Filament

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->required(),

                Forms\Components\TextInput::make('telephone') // ✅ Ajout du champ téléphone
                    ->label('Téléphone')
                    ->tel()
                    ->maxLength(20)
                    ->nullable(),

                Forms\Components\Select::make('role')
                    ->label('Rôle')
                    ->options([
                        'admin' => 'Admin',
                        'gerante' => 'Gérante',
                        'couturiere' => 'Couturière',
                        'client' => 'Client',
                    ])
                    ->required(),

                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email Vérifié')
                    ->default(now()),

                // ✅ Champ de mot de passe
                Forms\Components\TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                    ->rule(Password::defaults()),

                // ✅ Champ de confirmation du mot de passe
                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Confirmer le mot de passe')
                    ->password()
                    ->same('password')
                    ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('telephone') // ✅ Affichage du téléphone
                    ->label('Téléphone')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Rôle')
                    ->sortable(),

                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Email Vérifié')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
