<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SejourResource\Pages;
use App\Models\Bungalow;
use App\Models\Sejour;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;

class SejourResource extends Resource
{
    protected static ?string $model = Sejour::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Séjours';
    protected static ?string $modelLabel = 'Séjour';
    protected static ?string $pluralModelLabel = 'Séjours';

    public static function getCreateButtonLabel(): string
    {
        return 'Nouveau séjour';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section principale du séjour
                Forms\Components\Section::make('Informations du séjour')
                    ->schema([
                        Forms\Components\TextInput::make('codeResaSejour')
                            ->label('Code de réservation')
                            ->required()
                            ->unique(ignorable: fn($record) => $record)
                            ->maxLength(10),
                        Forms\Components\DatePicker::make('startDate')
                            ->label("Date d'arrivée")
                            ->required()
                            ->displayFormat('d/m/Y'),
                        Forms\Components\DatePicker::make('endDate')
                            ->label('Date de départ')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->after('startDate'),
                        Forms\Components\TextInput::make('nbrPersonnes')
                            ->label('Nombre de personnes')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(function (callable $get) {
                                $bungalowId = $get('bungalowId');

                                if (!$bungalowId) {
                                    return 4;  // Valeur par défaut
                                }

                                $bungalow = Bungalow::find($bungalowId);

                                if (!$bungalow) {
                                    return 4;
                                }

                                return $bungalow->typeBungalow === 'Mer' ? 2 : 4;
                            })
                            ->reactive()
                            ->helperText(function (callable $get) {
                                $bungalowId = $get('bungalowId');
                                if (!$bungalowId) {
                                    return "Sélectionnez d'abord un bungalow";
                                }

                                $bungalow = Bungalow::find($bungalowId);
                                if (!$bungalow) {
                                    return '';
                                }

                                $maxPersonnes = $bungalow->typeBungalow === 'Mer' ? 2 : 4;
                                return "Maximum {$maxPersonnes} personnes pour ce type de bungalow";
                            }),
                    ]),
                // Section de sélection du bungalow
                Forms\Components\Section::make('Hébergement')
                    ->schema([
                        Forms\Components\Select::make('bungalowId')
                            ->label('Bungalow')
                            ->options(function () {
                                $bungalows = Bungalow::all();
                                return $bungalows->mapWithKeys(function ($bungalow) {
                                    return [$bungalow->id => "{$bungalow->codeBungalow} - Type: {$bungalow->typeBungalow} (capacité: {$bungalow->capacite})"];
                                })->toArray();
                            })
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set) {
                                $set('nbrPersonnes', null);
                            })
                            ->placeholder('Sélectionner un bungalow'),
                    ]),
                // Note sur les autres activités
                Forms\Components\Section::make('Activités additionnelles')
                    ->schema([
                        Forms\Components\Placeholder::make('note_activites')
                            ->content('Une fois le séjour créé, vous pourrez réserver des activités additionnelles (garderie, restaurant, randonnées, etc.) à partir du numéro de séjour.')
                            ->inlineLabel()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codeResaSejour')
                    ->label('Code réservation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('startDate')
                    ->label('Arrivée')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('endDate')
                    ->label('Départ')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nbrPersonnes')
                    ->label('Personnes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bungalow.codeBungalow')
                    ->label('Bungalow')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bungalow.typeBungalow')
                    ->label('Type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Mer' => 'info',
                        'Jardin' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('bungalow')
                    ->relationship('bungalow', 'codeBungalow')
                    ->label('Filtrer par bungalow')
                    ->placeholder('Tous les bungalows'),
                Tables\Filters\Filter::make('dates')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label('Depuis')
                            ->placeholder('Date de début'),
                        Forms\Components\DatePicker::make('date_until')
                            ->label("Jusqu'à")
                            ->placeholder('Date de fin'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn($query) => $query->where('startDate', '>=', $data['date_from'])
                            )
                            ->when(
                                $data['date_until'],
                                fn($query) => $query->where('endDate', '<=', $data['date_until'])
                            );
                    })
                    ->label('Filtrer par période')
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['date_from'] ?? null) {
                            $indicators['date_from'] = 'À partir du ' . \Carbon\Carbon::parse($data['date_from'])->format('d/m/Y');
                        }

                        if ($data['date_until'] ?? null) {
                            $indicators['date_until'] = "Jusqu'au " . \Carbon\Carbon::parse($data['date_until'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Modifier'),
                Tables\Actions\DeleteAction::make()
                    ->label('Supprimer')
                    ->modalHeading('Supprimer le séjour')
                    ->modalDescription('Êtes-vous sûr de vouloir supprimer ce séjour ? Cette action est irréversible.')
                    ->modalSubmitActionLabel('Oui, supprimer')
                    ->modalCancelActionLabel('Annuler'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Supprimer la sélection')
                        ->modalHeading('Supprimer les séjours sélectionnés')
                        ->modalDescription('Êtes-vous sûr de vouloir supprimer les séjours sélectionnés ? Cette action est irréversible.')
                        ->modalSubmitActionLabel('Oui, supprimer')
                        ->modalCancelActionLabel('Annuler'),
                ]),
            ])
            ->emptyStateHeading('Aucun séjour trouvé')
            ->emptyStateDescription('Créez votre premier séjour en cliquant sur le bouton ci-dessous.')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label('Nouveau séjour')
                    ->url(route('filament.admin.resources.sejours.create'))
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Vous pourriez ajouter ici des relations vers d'autres activités
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSejours::route('/'),
            'create' => Pages\CreateSejour::route('/create'),
            'edit' => Pages\EditSejour::route('/{record}/edit'),
        ];
    }
}
