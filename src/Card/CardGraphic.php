<?php

namespace App\Card;

class CardGraphic
{
    private $cards = array(
        '🂡' => '#000000',
        '🂢' => '#000000',
        '🂣' => '#000000',
        '🂤' => '#000000',
        '🂥' => '#000000',
        '🂦' => '#000000',
        '🂧' => '#000000',
        '🂨' => '#000000',
        '🂩' => '#000000',
        '🂪' => '#000000',
        '🂫' => '#000000',
        '🂭' => '#000000',
        '🂮' => '#000000',
        '🂱' => '#d3000e',
        '🂲' => '#d3000e',
        '🂳' => '#d3000e',
        '🂴' => '#d3000e',
        '🂵' => '#d3000e',
        '🂶' => '#d3000e',
        '🂷' => '#d3000e',
        '🂸' => '#d3000e',
        '🂹' => '#d3000e',
        '🂺' => '#d3000e',
        '🂻' => '#d3000e',
        '🂽' => '#d3000e',
        '🂾' => '#d3000e',
        '🃑' => '#000000',
        '🃒' => '#000000',
        '🃓' => '#000000',
        '🃔' => '#000000',
        '🃕' => '#000000',
        '🃖' => '#000000',
        '🃗' => '#000000',
        '🃘' => '#000000',
        '🃙' => '#000000',
        '🃚' => '#000000',
        '🃛' => '#000000',
        '🃝' => '#000000',
        '🃞' => '#000000',
        '🃁' => '#d3000e',
        '🃂' => '#d3000e',
        '🃃' => '#d3000e',
        '🃄' => '#d3000e',
        '🃅' => '#d3000e',
        '🃆' => '#d3000e',
        '🃇' => '#d3000e',
        '🃈' => '#d3000e',
        '🃉' => '#d3000e',
        '🃊' => '#d3000e',
        '🃋' => '#d3000e',
        '🃍' => '#d3000e',
        '🃎' => '#d3000e',

    );

    public function dealCard()
    {
        return array_pop($this->arrCards);
    }

    public function initEnglishDeck()
    {
        $arrCards = array();
        foreach ($this->cards as $card => $color) {
            $arrCards[$card] = $color;
        }
        return $arrCards;
    }
    public function shuffleCards()
    {
        $arrCards = array();
        $shuffledarray = array();
        foreach ($this->cards as $card => $color) {
            $arrCards[$card] = $color;
        }


        $keys = array_keys($arrCards);
        shuffle($keys);

        foreach ($keys as $key) {
            $shuffledarray[$key] = $arrCards[$key];
        }

        return $shuffledarray;
    }
    

}
