<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# Ups!
@else
# Sveiki!
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Ar cieņu,<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
Ja jums ir problēmas ar pogas "{{ $actionText }}" noklikšķināšanu, kopējiet un ielīmējiet zemāk esošo saiti savā tīmekļa pārlūkprogrammā:

<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
