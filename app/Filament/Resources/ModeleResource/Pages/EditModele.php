<?php

namespace App\Filament\Resources\ModeleResource\Pages;

use App\Filament\Resources\ModeleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModele extends EditRecord
{
    protected static string $resource = ModeleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
