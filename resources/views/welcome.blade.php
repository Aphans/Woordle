<!DOCTYPE html>
<html>
<head>
    <title>Woordle - Get Started</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-DO1f2R7agAGbYK/cWZ4GSK0pgxhKQ0Bf8c4yFTdD5z0owAKV5K9/NpS1U6ZaU6nCnNw8Np/jGKzfgPptLlwxyQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            background-color: #F2F2F2;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            font-size: 48px;
            margin-bottom: 50px;
            color: #4E4E4E;
        }
        button {
            background-color: #FFBC42;
            color: white;
            font-size: 24px;
            font-weight: bold;
            padding: 20px 40px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0px 2px 2px rgba(0,0,0,0.2);
        }
        button:hover {
            transform: scale(1.1);
            box-shadow: 0px 4px 4px rgba(0,0,0,0.2);
        }
        .instructions {
            margin-top: 50px;
            color: #4E4E4E;
        }
        .instructions p {
            font-size: 18px;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .instructions p i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡Bienvenido al juego del Woordle!</h1>
        <form action="{{ url('/play') }}" method="GET">
            @csrf
            <button type="submit"><i class="fas fa-play"></i> ¡Comenzar a jugar!</button>
        </form>
        <div class="instructions">
            <p><i class="fas fa-info-circle"></i> El objetivo del juego es adivinar una palabra de 5 letras en 6 intentos.</p>
            <p><i class="fas fa-info-circle"></i> Cada intento consiste en escribir una palabra de 5 letras, y el juego te dirá cuántas letras están en la palabra buscada y en la posición correcta, y cuántas letras están en la palabra buscada pero en la posición incorrecta.</p>
            <p><i class="fas fa-info-circle"></i> Utiliza esta información para intentar adivinar la palabra buscada en el menor número de intentos posible.</p>
        </div>
    </div>
</body>
</html>