<!DOCTYPE html>
<html>
<head>
    <title>Woordle</title>
</head>
<body>
    <h1>Woordle</h1>
    <form action="{{ url('/play') }}" method="GET">
        @csrf
        <button type="submit">Comenzar</button>
    </form>
</body>
</html>
