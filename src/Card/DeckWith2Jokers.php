<?php

namespace App\Card;

class DeckWith2Jokers
{
    private $cards2 = array(
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
        '🃟' => '#000000',
        '🂿' => '#d3000e',
        

    );

    public function dealCard()
    {
        return array_pop($this->arrCards);
    }

    public function initEnglishDeck2Jokers()
    {
        
        return $this->cards2;
    }
    public function shuffleCards()
    {
        $arrCards = array();
        $shuffledarray = array();
        foreach ($this->cards2 as $card => $color) {
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