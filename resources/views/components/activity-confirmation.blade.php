@props([
    'title' => 'Réservation confirmée !',
    'activityType' => 'Activité',
    'activityName' => '',
    'date' => null,
    'startDate' => null,
    'endDate' => null,
    'personCount' => null,
    'duration' => null,
    'reservationCode' => null,
    'additionalInfo' => [],
])

<div class="confirm-resa">
    <div class="reservation-confirmation p-4 my-3 text-center border rounded">
        <h1 class="mb-3">{{ $title }}</h1>
        <div class="reservation-details text-start bg-light p-3 rounded mx-auto" style="max-width: 400px;">
            <p><strong>Type :</strong> {{ $activityType }} {{ $activityName }}</p>

            @if ($startDate && $endDate)
                <p><strong>Dates :</strong> Du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                    au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>

                @if (!$duration)
                    @php $duration = \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)); @endphp
                @endif
                <p><strong>Durée :</strong> {{ $duration }} jour(s)</p>
            @endif

            @if ($date && !$startDate)
                <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
            @endif

            @if ($personCount)
                <p><strong>Personnes :</strong> {{ $personCount }}</p>
            @endif

            @foreach ($additionalInfo as $label => $value)
                <p><strong>{{ $label }} :</strong> {{ $value }}</p>
            @endforeach
        </div>

        @if ($reservationCode)
            <p class="mt-3 fs-3 fw-semibold">Conservez votre numéro de réservation.
                Vous en aurez besoin pour d'autres réservations.</p>
            <div class="row justify-content-center">
                <p class="fw-bold col-5 fs-4 p-2">Numéro de réservation :</p>
                <p class="reservation-number fs-4 bg-light p-2 rounded col-5">{{ $reservationCode }}</p>
            </div>
        @endif
    </div>
</div>
