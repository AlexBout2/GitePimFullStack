@props([
    'showAll' => true,
    'fields' => [], // Champs spécifiques pour lesquels afficher les erreurs
    'title' => 'Veuillez corriger les erreurs suivantes:',
])

@if ($errors->any())
    <div class="alert alert-danger">
        <p class="mb-2">{{ $title }}</p>
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
