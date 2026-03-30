<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Groups</title>
</head>
<body>
<h1>Groups</h1>
@foreach($groups as $group)
    <section>
        <h2>{{ $group->name }}</h2>
        <ul>
            @forelse($group->users as $user)
                <li>{{ $user->name }} - {{ $user->email }}</li>
            @empty
                <li>No users in this group.</li>
            @endforelse
        </ul>
        <form method="POST" action="{{ route('groups.users.add', $group) }}">
            @csrf
            <label for="user_id_{{ $group->id }}">Add user:</label>
            <select name="user_id" id="user_id_{{ $group->id }}">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
            <button type="submit">Add to group</button>
        </form>
    </section>
@endforeach
</body>
</html>
