<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function play(Request $request)
{
    $word = DB::table('words')->inRandomOrder()->first();
    $guesses = [];
    $hiddenWord = str_repeat('*', strlen($word->word));
    $remainingAttempts = 10; // Se establece el número de intentos iniciales
    $request->session()->put('hiddenWord', $hiddenWord);
    $request->session()->put('guesses', $guesses);
    $request->session()->put('remainingAttempts', $remainingAttempts);
    return view('play', compact('hiddenWord', 'guesses', 'remainingAttempts', 'word'));
}


    public function guess(Request $request)
    {
        $this->validate($request, [
            'guess' => 'required|alpha|max:1'
        ]);

        $guess = strtoupper($request->guess);
        $word = DB::table('words')->first();
        $hiddenWord = str_split($request->session()->get('hiddenWord')); 
        $guesses = $request->session()->get('guesses');
        $remainingAttempts = $request->session()->get('remainingAttempts');

        if (in_array($guess, $guesses)) {
            $request->session()->flash('message', 'Ya intentaste con la letra ' . $guess . '. Intenta con otra letra.');
            return redirect('/play');
        }

        $guesses[] = $guess;
        $request->session()->put('guesses', $guesses);

        if (strpos($word->word, $guess) !== false) {
            for ($i = 0; $i < strlen($word->word); $i++) {
                if ($word->word[$i] == $guess) {
                    $hiddenWord[$i] = $guess;
                }
            }
            $request->session()->put('hiddenWord', implode('', $hiddenWord)); // Se convierte de nuevo en una cadena
        } else {
            $remainingAttempts--;
            $request->session()->put('remainingAttempts', $remainingAttempts);
        }

        if (implode('', $hiddenWord) == $word->word) {
            return view('result', [
                'word' => $word,
                'hiddenWord' => implode('', $hiddenWord),
                'guesses' => $guesses,
                'remainingAttempts' => $remainingAttempts,
                'message' => '¡Felicidades, adivinaste la palabra!'
            ]);
        } else if ($remainingAttempts == 0) {
            return view('result', [
                'word' => $word,
                'hiddenWord' => $word->word,
                'guesses' => $guesses,
                'remainingAttempts' => $remainingAttempts,
                'message' => 'Lo siento, ya no tienes intentos disponibles. La palabra era ' . $word->word
            ]);
        } else {
            return view('play', compact('hiddenWord', 'guesses', 'remainingAttempts', 'word'));

        }
    }
}
