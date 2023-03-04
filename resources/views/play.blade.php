<!DOCTYPE html>
<html>
<head>
    <title>Woordle - Juego</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div id="game-container">
        <h1>Woordle</h1>
        <h2 id="hidden-word" data-word="{{ $word->word }}">{{ $hiddenWord }}</h2>

        <div id="game-board">
            <table>
                @for ($i = 0; $i < 4; $i++)
                    <tr>
                        @for ($j = 0; $j < 5; $j++)
                            <td>
                                <input type="text" maxlength="1">
                            </td>
                        @endfor
                    </tr>
                @endfor
            </table>
        </div>

        <div id="game-controls">
            <button id="reset-button" onclick="startGame()">Reiniciar juego</button>
            <div id="attempts">Intentos restantes: <span id="remaining-attempts">{{ $remainingAttempts }}</span></div>
            <div id="score">Puntuación: <span id="game-score">0</span></div>
            <div id="timer">Tiempo restante: <span id="game-timer">60</span></div>
        </div>

        <div id="game-messages"></div>
    </div>

    <div id="word-list">
        <h2>Palabras acertadas:</h2>
        <ul id="guessed-words"></ul>
    </div>
</html>
<script>
var remainingAttempts = 3;
var hiddenWord = document.getElementById('hidden-word');
var word = hiddenWord.getAttribute('data-word').toUpperCase();
var currentRow = 1;
var currentInput = 1;
var correctWords = [];
var score = 0;

function updateHiddenWord(event) {
    var input = event.target;
    var guess = input.value.toUpperCase();
    input.removeEventListener('keyup', updateHiddenWord);

    var newHiddenWord = '';
    var letterFound = false;
    var letterInWord = false;

    for (var i = 0; i < word.length; i++) {
        if (guess === word.charAt(i)) {
            newHiddenWord += guess;
            letterFound = true;
            if (i === currentInput - 1) {
                letterInWord = true;
            }
        } else {
            newHiddenWord += hiddenWord.textContent.charAt(i);
        }
    }

    hiddenWord.textContent = newHiddenWord;

    if (letterInWord) {
        input.style.backgroundColor = 'green';
    } else if (letterFound) {
        input.style.backgroundColor = 'yellow';
    } else {
        input.style.backgroundColor = 'red';
    }

    input.disabled = true;
    currentInput++;

    if (currentInput > 5) {
        checkWord();
    } else {
        var nextInput = document.querySelector('tr:nth-of-type(' + currentRow + ') input:nth-of-type(' + currentInput + ')');
        nextInput.focus();
    }
}

function checkWord() {
    var currentGuess = hiddenWord.textContent.toUpperCase();
    if (currentGuess === word) {
        alert('¡Felicidades, has ganado!');
        correctWords.push(currentGuess);
        score += 10;
        document.getElementById('game-score').textContent = score;
        var guessedWordsList = document.getElementById('guessed-words');
        var newGuessedWord = document.createElement('li');
        newGuessedWord.textContent = currentGuess;
        guessedWordsList.appendChild(newGuessedWord);
        resetRow();
    } else if (remainingAttempts === 1) {
        alert('¡Lo siento, has perdido! La palabra era ' + word);
        resetGame();
    } else {
        remainingAttempts--;
        document.getElementById('remaining-attempts').textContent = 'Intentos restantes: ' + remainingAttempts;
        resetRow();
    }
}

function resetRow() {
    var inputs = document.querySelectorAll('tr:nth-of-type(' + currentRow + ') input[type="text"]');
    var wordFound = true;
    inputs.forEach(function(input) {
        input.disabled = false;
        input.style.backgroundColor = 'white';
        if (input.value.toUpperCase() !== word.charAt(currentInput - 1)) {
            wordFound = false;
        }
        input.value = '';
        input.addEventListener('keyup', updateHiddenWord);
    });
    if (!wordFound) {
        score--;
        document.getElementById('game-score').textContent = score;
    }
    currentRow++;
    currentInput = 1;
    if (currentRow > 4) {
        resetGame();
    } else {
        var firstInput = document.querySelector('tr:nth-of-type(' + currentRow + ') input:nth-of-type(1)');
        firstInput.focus();
    }
}

function resetGame() {
    hiddenWord.textContent = '{{ $hiddenWord }}';
    hiddenWord.setAttribute('data-word', '{{ $word->word }}');
    if (correctWords.length > 0) {
        document.getElementById('game-messages').textContent = 'Palabras acertadas: ' + correctWords.join(', ');
    }

    remainingAttempts = 3;
    currentRow = 1;
    currentInput = 1;
    correctWords = [];
    score = 0;

    document.getElementById('game-score').textContent = score;
    document.getElementById('remaining-attempts').textContent = 'Intentos restantes: ' + remainingAttempts;

    var inputs = document.querySelectorAll('input[type="text"]');
    inputs.forEach(function(input) {
        input.disabled = false;
        input.style.backgroundColor = 'white';
        input.value = '';
        input.addEventListener('keyup', updateHiddenWord);
    });

    hiddenWord.textContent = document.getElementById('hidden-word').getAttribute('data-word');
    var firstInput = document.querySelector('tr:nth-of-type(1) input:nth-of-type(1)');
    firstInput.focus();

    var guessedWordsList = document.getElementById('guessed-words');
    guessedWordsList.innerHTML = '';

    document.getElementById('game-messages').textContent = '';

    var resetButton = document.getElementById('reset-button');
    resetButton.disabled = false;
    resetButton.textContent = 'Reiniciar juego';

    clearInterval(timer);
    timeLeft = 60;
    document.getElementById('game-timer').textContent = timeLeft;
    timer = setInterval(updateTimer, 1000);
}

function guessLetter() {
var input = document.getElementById('word-input').value.toLowerCase();
if (input.length !== 1 || !isLetter(input)) {
document.getElementById('game-messages').textContent = 'Por favor, ingresa una letra válida';
return;
}
if (guessedLetters.includes(input)) {
document.getElementById('game-messages').textContent = 'Ya has adivinado la letra ' + input + ', intenta otra';
return;
}
guessedLetters.push(input);
if (currentWord.includes(input)) {
document.getElementById('game-messages').textContent = '¡Adivinaste la letra ' + input + '!';
updateWordDisplay(currentWord, guessedLetters);
if (!document.getElementById('word-display').textContent.includes('_')) {
document.getElementById('game-messages').textContent = '¡Felicidades! ¡Ganaste!';
correctWords.push(currentWord);
resetGame();
}
} else {
maxGuesses--;
document.getElementById('game-messages').textContent = 'La letra ' + input + ' no está en la palabra. Te quedan ' + maxGuesses + ' intentos.';
document.getElementById('guesses-remaining').textContent = 'Intentos restantes: ' + maxGuesses;
if (maxGuesses === 0) {
document.getElementById('game-messages').textContent = '¡Perdiste! La palabra era "' + currentWord + '"';
resetGame();
}
}
}

function isLetter(char) {
return /[a-z]/.test(char);
}

function chooseWord(wordsArray) {
return wordsArray[Math.floor(Math.random() * wordsArray.length)];
}

function startGame() {
  // Reset game state
  remainingAttempts = 3;
  hiddenWord.textContent = Array(word.length).fill('_').join('');
  currentRow = 1;
  currentInput = 1;
  correctWords = [];
  score = 0;

  // Reset UI
  document.getElementById('remaining-attempts').textContent = 'Intentos restantes: ' + remainingAttempts;
  document.getElementById('game-score').textContent = score;
  document.getElementById('guessed-words').innerHTML = '';
  document.getElementById('game-messages').textContent = '';

  // Add event listeners to inputs
  var inputs = document.querySelectorAll('input[type="text"]');
  inputs.forEach(function(input) {
    input.disabled = false;
    input.value = '';
    input.addEventListener('keyup', updateHiddenWord);
  });

  // Set focus to first input
  var firstInput = document.querySelector('tr:nth-of-type(1) input:nth-of-type(1)');
  firstInput.focus();
}


startGame();

</script>


<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
    }

    #game-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }

    h1 {
        font-size: 48px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }

    h2 {
        font-size: 36px;
        font-weight: normal;
        margin-bottom: 40px;
        text-align: center;
        letter-spacing: 10px;
    }

    table {
        margin: 0 auto;
        border-collapse: collapse;
    }

    td {
        text-align: center;
        padding: 10px;
        border: 1px solid #ccc;
    }

    input[type="text"] {
        width: 50px;
        height: 50px;
        font-size: 24px;
        text-align: center;
        border: none;
        background-color: #f2f2f2;
    }

    input[type="text"]:disabled {
        background-color: #ccc;
    }

    #game-controls {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #ccc;
    }

    #reset-button {
        font-size: 20px;
        font-weight: bold;
        border: none;
        background-color: #f2f2f2;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
    }

    #reset-button:hover {
        background-color: #ccc;
    }

    #attempts, #score {
        font-size: 24px;
        font-weight: bold;
    }

    #game-messages {
        margin-top: 20px;
        font-size: 24px;
        font-weight: bold;
        text-align: center;
    }

    .correct-guess {
        color: green;
    }

    .incorrect-guess {
        color: red;
    }

    #word-list {
        margin-top: 40px;
        font-size: 24px;
        font-weight: bold;
        text-align: center;
    }

    #word-list ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    #word-list li {
        display: inline-block;
        margin: 10px;
        padding: 10px 20px;
        background-color: #f2f2f2;
        border-radius: 5px;
    }

    #word-list li:nth-of-type(odd) {
        background-color: #ccc;
    }
</style>