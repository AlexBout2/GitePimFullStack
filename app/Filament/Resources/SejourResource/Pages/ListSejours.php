<?php

namespace App\Filament\Resources\SejourResource\Pages;

use App\Filament\Resources\SejourResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListSejours extends ListRecords
{
    protected static string $resource = SejourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nouvelle réservation'),
        ];
    }
}
