@component('mail::message')
# Hello,

@php
    $deadline = $ticket->dateline_extension ?? $ticket->dateline;
    $daysOverdue = \Carbon\Carbon::parse($deadline)->diffInDays(now());
@endphp

This UCUA Observation has **exceeded its action dateline by {{ $daysOverdue }} day(s)** and is still awaiting action. Please take the necessary action as soon as possible.<br>

URL: <a href="{{ route('ShowSelectedTicket',['category'=>'Open','ticketId'=>$ticket->id]) }}">{{ $ticket->ticket_id }}</a> <br>

<div style="font-weight:bold;font-size:18px;">Observation #{{ $ticket->id }}</div>
<div style="font-weight:bold;font-size:18px;">Ticket ID: {{ $ticket->ticket_id }}</div>
<div style="font-weight:bold;font-size:18px;color:#c0392b;">Dateline: {{ \Carbon\Carbon::parse($deadline)->format('d/m/Y') }} (Overdue)</div>
<div class="text-gray-600">Reported by:</div>
<div>{{ $ticket->name }}</div>
<div>{{ $ticket->email }}</div>
<div>{{ $ticket->phone_number }}</div>
<div>{{ $ticket->staff_id }}</div>
<div>{{ $ticket->plant->name }}</div>
@if ($ticket->department_id != 0)
<div>{{ $ticket->department->name }}</div>
@if ($ticket->sub_department_id != 0)
<div>{{ $ticket->sub_department->name }}</div>
@endif
@else
<div>{{ $ticket->department_other }}</div>
@endif
<div class="text-gray-600">Reported on: {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i A') }}</div>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
