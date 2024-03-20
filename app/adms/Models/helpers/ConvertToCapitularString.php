<?php

namespace App\adms\Models\helpers;

class ConvertToCapitularString
{
    public static function format(string $str): string
    {
        $wordExceptions = array_flip(["da", "de", "do", "das", "dos"]);
        $words = explode(' ', $str);

        foreach ($words as $index => $palavra) {
            // Convert the word to lowercase
            $wordLower = strtolower($palavra);
            // Verify if the word is not an exception
            if (!isset($wordExceptions[$wordLower])) {
                // Force the first letter of the word to be uppercase
                $words[$index] = mb_strtoupper(mb_substr($palavra, 0, 1)) . mb_strtolower(mb_substr($palavra, 1));
            } else {
                // Convert the word to lowercase if it is an exception
                $words[$index] = $wordLower;
            }
        }
        // Join the words into a string
        return implode(' ', $words);
    }
}