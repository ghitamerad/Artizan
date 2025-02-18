<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModeleResource\Pages;
use App\Models\modele;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;

class ModeleResource extends Resource
{
    protected static ?string $model = modele::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('nom')->required(),
                Textarea::make('description'),
                Select::make('categorie_id')
                    ->relationship('categorie', 'nom') // Assure-toi que la colonne est bien "nom" dans ta table "categories"
                    ->required(),
                TextInput::make('prix')->numeric(),
                TextInput::make('patron'),
                TextInput::make('xml'),
                Toggle::make('en_stock')->label('En Stock')->default(true), // Ajout du champ "En Stock"
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')->sortable()->searchable(),
                TextColumn::make('description')->limit(50),
                TextColumn::make('categorie.nom')->sortable()->searchable(), // Vérifie bien que la colonne s'appelle "nom"
                TextColumn::make('prix')->sortable(),
                BooleanColumn::make('en_stock')->label('En Stock')->sortable(), // Affichage de l'état en stock
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(), ])
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModeles::route('/'),
            'create' => Pages\CreateModele::route('/create'),
            'edit' => Pages\EditModele::route('/{record}/edit'),
        ];
    }
}
