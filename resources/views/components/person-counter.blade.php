@props([
    'personCount' => 2,
    'minPersons' => 1,
    'maxPersons' => 10,
    'fieldName' => 'nbrPersonnes',
    'label' => 'Nombre de personnes',
    'showLabels' => true, // Afficher "personne(s)" ou juste les chiffres
    'extraClasses' => [],
    'capaciteInfo' => null, // Information sur la capacité de l'activité
])

<div class="form-group mb-4">
    <label for="{{ $fieldName }}" class="mb-3 d-block text-center">{{ $label }}</label>

    <div class="d-flex justify-content-center">
        <select class="form-select w-50 @error($fieldName) is-invalid @enderror {{ implode(' ', $extraClasses) }}"
            id="{{ $fieldName }}" name="{{ $fieldName }}" required>
            @for ($i = $minPersons; $i <= 10; $i++)
                <option value="{{ $i }}" {{ $personCount == $i ? 'selected' : '' }}>
                    {{ $i }} {{ $showLabels ? ($i > 1 ? 'personnes' : 'personne') : '' }}
                </option>
            @endfor
        </select>
    </div>

    @if ($capaciteInfo)
        <p class="text-center mt-2">
            <small>{{ $capaciteInfo }}</small>
        </p>
    @endif

    @error($fieldName)
        <div class="invalid-feedback d-block text-center">{{ $message }}</div>
    @enderror
</div>
