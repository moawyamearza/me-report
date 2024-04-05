<?php

namespace App\Card;
/**
 * Class DeckWith2Jokers
 *
 * Represents a deck of playing cards with graphics and colors.
 */
class DeckWith2Jokers
{
    /**
    * @var array<string, string> An array representing the deck of cards with graphics, colors
    */
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
    /**
     * @return array<string, string> An array of cards with their corresponding colors.
     */
    public function initEnglishDeck2Jokers()
    {
        return $this->cards2;
    }

    /**
     * @return array<string, string> A shuffled deck of cards with graphics and colors.
     */
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
