@component('mail::message')
# Hello{{ $recipientName ? ' ' . $recipientName : '' }},

@php
    $count = count($rows);
    $oldest = collect($rows)->max('days_overdue');
    $cell = fn ($text) => str_replace('|', '/', (string) $text);
@endphp

You have **{{ $count }} UCUA {{ \Illuminate\Support\Str::plural('observation', $count) }}** past {{ $count == 1 ? 'its' : 'their' }} action dateline and still open. The oldest is **{{ $oldest }} {{ \Illuminate\Support\Str::plural('day', $oldest) }} overdue**. Please take the necessary action as soon as possible.

@component('mail::table')
| Observation | Responsible | Plant | Dateline | Overdue |
|:--|:--|:--|:--|--:|
@foreach ($rows as $row)
| [#{{ $row['id'] }}]({{ route('ShowSelectedTicket', ['category' => 'Open', 'ticketId' => $row['id']]) }}) | {{ $cell($row['responsible']) }} | {{ $cell($row['plant']) }} | {{ $row['dateline'] }} | {{ $row['days_overdue'] }} {{ \Illuminate\Support\Str::plural('day', $row['days_overdue']) }} |
@endforeach
@endcomponent

You will receive this summary once a day while any of these observations remain open past their dateline.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
