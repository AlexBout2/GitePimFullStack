<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Sejour;
use Filament\Tables\Columns\TextColumn;

class Bungalows extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Réservations de Bungalows';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Sejour::query()->with('bungalow')->latest('startDate')
            )
            ->columns([
                TextColumn::make('bungalow.codeBungalow')
                    ->label('Code Bungalow')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('bungalow.typeBungalow')
                    ->label('Type Bungalow')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('startDate')
                    ->label('Date Début')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                TextColumn::make('endDate')
                    ->label('Date Fin')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                TextColumn::make('nbrPersonnes')
                    ->label('Nombre de Personnes')
                    ->numeric()
                    ->sortable(),
                    
                TextColumn::make('codeResaSejour')
                    ->label('N° Séjour')
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('startDate', 'desc')
            ->paginated([10, 25, 50, 100]);
    }
}
