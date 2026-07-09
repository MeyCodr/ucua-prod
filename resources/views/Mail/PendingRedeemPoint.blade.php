@component('mail::message')
# Hello SHE Admin PHN,

New Point Redemption Request has been created for you to verify.<br>
Details of the observation are as follow.<br>

URL: <a href="{{ route('admin.redeem.list.staff',['status' => 'Pending', 'staff_id' => $submitter->staff_id]) }}">Pending Request</a> <br>

<div class="text-gray-600">Request by:</div>
<div>{{ $submitter->name }}</div>
<div>{{ $submitter->email }}</div>
<div>{{ $submitter->phone_number }}</div>
<div>{{ $submitter->staff_id }}</div>

<div class="text-gray-600 mt-4">Redemption Details:</div>
@if ($redeemId->points == 10)
<div>10 points - RM20 Coupon/Voucher</div>
@elseif ($redeemId->points == 50)
<div>50 points - RM100 Coupon/Voucher</div>
@else
<div>100 points - RM200 Coupon/Voucher</div>
@endif
<div class="text-gray-600">Reported on: {{ \Carbon\Carbon::parse($submitter->created_at)->format('d/m/Y H:i A') }}</div>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
