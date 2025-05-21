<?php

namespace App\Filament\Resources\SejourResource\Pages;

use App\Filament\Resources\SejourResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditSejour extends EditRecord
{
    protected static string $resource = SejourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Supprimer'),
        ];
    }
}
