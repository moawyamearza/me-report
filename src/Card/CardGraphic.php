<?php

namespace App\Card;

class CardGraphic
{
    private $cards = array(
        '🂡' => array('#000000',"1"),
        '🂢' => array('#000000',"2"),
        '🂣' => array('#000000',"3"),
        '🂤' => array('#000000',"4"),
        '🂥' => array('#000000',"5"),
        '🂦' => array('#000000',"6"),
        '🂧' => array('#000000',"7"),
        '🂨' => array('#000000',"8"),
        '🂩' => array('#000000',"9"),
        '🂪' => array('#000000',"10"),
        '🂫' => array('#000000',"11"),
        '🂭' => array('#000000',"12"),
        '🂮' => array('#000000',"13"),
        '🂱' => array('#d3000e',"1"),
        '🂲' => array('#d3000e',"2"),
        '🂳' => array('#d3000e',"3"),
        '🂴' => array('#d3000e',"4"),
        '🂵' => array('#d3000e',"5"),
        '🂶' => array('#d3000e',"6"),
        '🂷' => array('#d3000e',"7"),
        '🂸' => array('#d3000e',"8"),
        '🂹' => array('#d3000e',"9"),
        '🂺' => array('#d3000e',"10"),
        '🂻' => array('#d3000e',"11"),
        '🂽' => array('#d3000e',"12"),
        '🂾' => array('#d3000e',"13"),
        '🃑' => array('#000000',"1"),
        '🃒' => array('#000000',"2"),
        '🃓' => array('#000000',"3"),
        '🃔' => array('#000000',"4"),
        '🃕' => array('#000000',"5"),
        '🃖' => array('#000000',"6"),
        '🃗' => array('#000000',"7"),
        '🃘' => array('#000000',"8"),
        '🃙' => array('#000000',"9"),
        '🃚' => array('#000000',"10"),
        '🃛' => array('#000000',"11"),
        '🃝' => array('#000000',"12"),
        '🃞' => array('#000000',"13"),
        '🃁' => array('#d3000e',"1"),
        '🃂' => array('#d3000e',"2"),
        '🃃' => array('#d3000e',"3"),
        '🃄' => array('#d3000e',"4"),
        '🃅' => array('#d3000e',"5"),
        '🃆' => array('#d3000e',"6"),
        '🃇' => array('#d3000e',"7"),
        '🃈' => array('#d3000e',"8"),
        '🃉' => array('#d3000e',"9"),
        '🃊' => array('#d3000e',"10"),
        '🃋' => array('#d3000e',"11"),
        '🃍' => array('#d3000e',"12"),
        '🃎' => array('#d3000e',"13"),

    );


    public function initEnglishDeck()
    {
        $arrCards = array();
        foreach ($this->cards as $card => $color) {
            $arrCards[$card] = $color[0];
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
