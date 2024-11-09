<!DOCTYPE html>
<html>
<head>
    <title>Import Users</title>
</head>
<body>
    <h1>Import Users</h1>
    
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Import Users</button>
    </form>
</body>
</html>
