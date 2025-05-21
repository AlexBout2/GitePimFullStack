<?php

namespace App\Filament\Resources\SejourResource\Pages;

use App\Filament\Resources\SejourResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreateSejour extends CreateRecord
{
    protected static string $resource = SejourResource::class;

    protected function beforeValidate(): void
    {
        $data = $this->form->getRawState();

        if (isset($data['startDate'], $data['endDate'], $data['bungalowId'])) {
            $existingBooking = Sejour::where('bungalowId', $data['bungalowId'])
                ->where(function ($query) use ($data) {
                    $query
                        ->whereBetween('startDate', [$data['startDate'], $data['endDate']])
                        ->orWhereBetween('endDate', [$data['startDate'], $data['endDate']])
                        ->orWhere(function ($query) use ($data) {
                            $query
                                ->where('startDate', '<=', $data['startDate'])
                                ->where('endDate', '>=', $data['endDate']);
                        });
                });

            if ($existingBooking->exists()) {
                $this->form->addValidationError(
                    'bungalowId',
                    "Ce bungalow n'est pas disponible pour les dates sélectionnées."
                );
            }
        }
    }
}
