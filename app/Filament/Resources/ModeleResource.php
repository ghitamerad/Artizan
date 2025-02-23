<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModeleResource\Pages;
use App\Models\Modele;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\Page;

class ModeleResource extends Resource
{
    protected static ?string $model = Modele::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->label('Nom')
                    ->required(),

                Forms\Components\Textarea::make('description') // ✅ Ajout de la description
                    ->label('Description')
                    ->nullable(),

                Forms\Components\Select::make('categorie_id')
                    ->label('Catégorie')
                    ->relationship('categorie', 'nom')
                    ->required(),

                Forms\Components\TextInput::make('prix')
                    ->label('Prix')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('patron')
                    ->label('Patron')
                    ->nullable(),

                Forms\Components\Textarea::make('xml')
                    ->label('Fiche de mesures (XML)')
                    ->nullable(),

                Forms\Components\Toggle::make('en_stock') // ✅ Ajout de en_stock
                    ->label('En Stock')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->label('Nom')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description') // ✅ Ajout de la description
                    ->label('Description')
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('categorie.nom')
                    ->label('Catégorie')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('prix')
                    ->label('Prix')
                    ->sortable(),

                Tables\Columns\IconColumn::make('en_stock') // ✅ Icônes ✔️❌ pour en_stock
                    ->label('En Stock')
                    ->sortable()
                    ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn ($state) => $state ? 'success' : 'danger'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            'index' => Pages\ListModeles::route('/'),
            'create' => Pages\CreateModele::route('/create'),
            'edit' => Pages\EditModele::route('/{record}/edit'),
        ];
    }
}
