@component('mail::message')
# Hello {{ $ticket->name }},

@if ($approval->approver_status == "Completed")
<div>Your UCUA Observation has been completed and the issue has been resolved. You has received one point. Thank you.</div>
<br>
<div>Completed by (SHE ADMIN PHN):</div>
@else
<div>Your UCUA Observation has been declined.</div>
<br>
<div>Declined by:</div>
@endif
<div>{{ $approval->approver->email }}</div>
<div>On {{ \Carbon\Carbon::parse($approval->approved_at)->format('d/m/Y H:i A') }}</div>
@if ($approval->approver_remark != NULL)
<div>Remark: {{ $approval->approver_remark }}</div>
@endif
<div>Details of the observation are as follow.</div>

<hr>
URL: <a href="{{ route('SearchTicketDetail',['status'=>$ticket->status,'ticket_id'=>$ticket->id]) }}">{{ $ticket->ticket_id }}</a> <br>
<hr>
<div style="font-weight:bold;font-size:18px;">Observation #{{ $ticket->id }}</div>
<div style="font-weight:bold;font-size:18px;">Ticket ID: {{ $ticket->ticket_id }}</div>
<div style="font-weight:bold;font-size:18px;">Dateline: {{ \Carbon\Carbon::parse($ticket->dateline)->format('d/m/Y') }}</div>
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
