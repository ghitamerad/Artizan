<?php

namespace App\Filament\Resources\UserResource\Pages;
 
use App\Filament\Resources\UserResource;
use App\Http\Controllers\UserController;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
 
class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
 
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Récupérer l'utilisateur
        $user = $this->record;
 
        // Appeler la méthode update du UserController
        $request = new Request($data);
        app(UserController::class)->update($request, $user);
 
        return $data;
    }
 
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}