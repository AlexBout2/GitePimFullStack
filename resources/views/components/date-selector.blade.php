@props([
    'mode' => 'single', // 'single' ou 'range'
    'startDate' => '',
    'endDate' => '',
    'label' => 'Date',
    'startLabel' => 'Début',
    'endLabel' => 'Fin',
    'minDate' => null,
    'maxDate' => null,
])

<div class="form-group mb-4">
    <h2 class="mb-5 text-center">{{ $label }}</h2>

    @if ($mode === 'single')
        <div class="d-flex justify-content-center">
            <div class="col-md-6">
                <label for="date">Date</label>
                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date"
                    required value="{{ $startDate }}" min="{{ $minDate ?? date('Y-m-d') }}"
                    {{ $maxDate ? "max=$maxDate" : '' }}>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-6">
                <label for="startDate">{{ $startLabel }}</label>
                <input type="date" class="form-control @error('startDate') is-invalid @enderror" id="startDate"
                    name="startDate" required value="{{ $startDate }}" min="{{ $minDate ?? date('Y-m-d') }}"
                    {{ $maxDate ? "max=$maxDate" : '' }}>
                @error('startDate')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6">
                <label for="endDate">{{ $endLabel }}</label>
                <input type="date" class="form-control @error('endDate') is-invalid @enderror" id="endDate"
                    name="endDate" required value="{{ $endDate }}" min="{{ $minDate ?? date('Y-m-d') }}"
                    {{ $maxDate ? "max=$maxDate" : '' }}>
                @error('endDate')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    @endif
</div>
