@props([
    'showAll' => true,
    'fields' => [], // Champs spécifiques pour lesquels afficher les erreurs
    'title' => 'Après syncronisation, il s\'emble il y avoir un soucis',
])

@if ($errors->any())
    <div class="alert alert-info">
        <p class="mb-2 text-center">{{ $title }}</p>
        <ul class="mb-0">
            @if ($showAll)
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            @else
                @foreach ($fields as $field)
                    @error($field)
                        <li>{{ $message }}</li>
                    @enderror
                @endforeach
            @endif
        </ul>
    </div>
@endif
