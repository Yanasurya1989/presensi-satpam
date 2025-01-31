<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simpan Timestamp</title>
</head>

<body>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('save.timestamp') }}" method="POST">
        @csrf
        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
        <button type="submit">Simpan Timestamp</button>
    </form>

</body>

</html>
