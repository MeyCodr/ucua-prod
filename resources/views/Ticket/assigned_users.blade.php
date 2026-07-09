<ul>
    <strong>Person in Charge:</strong>
    @foreach ($assignedUsers as $user)
        <li>{{ $user->name }}</li>
        <li>{{ $user->designation }}</li>
        <li>{{ $user->email }}</li>
        <li>{{ $user->phone_number }}</li>
        @break
    @endforeach
</ul>
