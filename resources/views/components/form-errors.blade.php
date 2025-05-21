@props([
    'showAll' => true,
    'fields' => [], // Champs spécifiques pour lesquels afficher les erreurs
    'title' => 'Après syncronisation, il s\'emble il y avoir un soucis',
])

@if ($errors->any())
    <div class="alert bg-secondary">
        <p class="mb-2 fs-5">{{ $title }}</p>
        <div class="text-center fs-5 bg-event">
            @if ($showAll)
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            @else
                @foreach ($fields as $field)
                    @error($field)
                        <div>{{ $message }}</div>
                    @enderror
                @endforeach
            @endif
        </div>
    </div>
@endif
