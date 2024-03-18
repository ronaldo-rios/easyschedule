<?php

namespace App\adms\Models\helpers;

class ConvertToCapitularString
{
    public static function format(string $str): string
    {
        $palavrasExcecoes = array_flip(["Da", "De", "Do", "Das", "Dos"]);

        $palavras = explode(" ", $str);

        foreach ($palavras as &$palavra) {
            $palavra = mb_convert_case(mb_strtolower($palavra, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            /** Convert to lowercase and check if the word is in the exception list */
            $palavra = isset($palavrasExcecoes[$palavra]) ? mb_strtolower($palavra, 'UTF-8') : $palavra;
        }

        $stringFormatada = implode(" ", $palavras);
        $stringFormatada = strtr($stringFormatada, $palavrasExcecoes);

        return $stringFormatada;
    }
}