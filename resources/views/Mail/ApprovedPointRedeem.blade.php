@component('mail::message')
# Hello {{ $submitter->name }},

<div>Your point redemption request has been completed and the <strong>{{ $redeem->points }}</strong> points have been credited to your
    account. Please contact SHE Admin for the process of withdrawal the voucher. Thank you.</div>
<br>

<div>Details of your point balance are as follow.</div>
<div>Total Points Earned: <strong>{{ $point_total }}</strong> points</div>
<div>Points Pending Redemption Approval: <strong>{{ $point_floating }}</strong> points</div>
<div>Points Redeemed: <strong>{{ $point_redeem }}</strong> points</div>
<div>Total Balance Points: <strong>{{ $point_balance }}</strong> points</div>
<br>

<hr>
URL: <a
    href="{{ route('redeem.point', ['staff_id' => $submitter->staff_id, 'status'=>'Approved']) }}">#{{ $redeem->id }}</a>
<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
