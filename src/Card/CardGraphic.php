<?php

namespace App\Card;

/**
 * Class CardGraphic
 *
 * Represents a deck of playing cards with graphics and colors.
 */
class CardGraphic
{
    private $cards;
    
    public function __construct()
    {
        // Initialize the deck of cards
        $this->initializeDeck();
    }
    /**
     * Initializes the deck of cards.
     */
    private function initializeDeck()
    {
        
        // Define the cards with their graphics, colors, and values
        $this->cards = [
            '🂡' => ['#000000', "1", '🂡'],
            '🂢' => ['#000000', "2", '🂢'],
            '🂣' => ['#000000', "3", '🂣'],
            '🂤' => ['#000000', "4", '🂤'],
            '🂥' => ['#000000', "5", '🂥'],
            '🂦' => ['#000000', "6", '🂦'],
            '🂧' => ['#000000', "7", '🂧'],
            '🂨' => ['#000000', "8", '🂨'],
            '🂩' => ['#000000', "9", '🂩'],
            '🂪' => ['#000000', "10", '🂪'],
            '🂫' => ['#000000', "11", '🂫'],
            '🂭' => ['#000000', "12", '🂭'],
            '🂮' => ['#000000', "13", '🂮'],
            '🂱' => ['#d3000e', "1", '🂱'],
            '🂲' => ['#d3000e', "2", '🂲'],
            '🂳' => ['#d3000e', "3", '🂳'],
            '🂴' => ['#d3000e', "4", '🂴'],
            '🂵' => ['#d3000e', "5", '🂵'],
            '🂶' => ['#d3000e', "6", '🂶'],
            '🂷' => ['#d3000e', "7", '🂷'],
            '🂸' => ['#d3000e', "8", '🂸'],
            '🂹' => ['#d3000e', "9", '🂹'],
            '🂺' => ['#d3000e', "10", '🂺'],
            '🂻' => ['#d3000e', "11", '🂻'],
            '🂽' => ['#d3000e', "12", '🂽'],
            '🂾' => ['#d3000e', "13", '🂾'],
            '🃑' => ['#000000', "1", '🃑'],
            '🃒' => ['#000000', "2", '🃒'],
            '🃓' => ['#000000', "3", '🃓'],
            '🃔' => ['#000000', "4", '🃔'],
            '🃕' => ['#000000', "5", '🃕'],
            '🃖' => ['#000000', "6", '🃖'],
            '🃗' => ['#000000', "7", '🃗'],
            '🃘' => ['#000000', "8", '🃘'],
            '🃙' => ['#000000', "9", '🃙'],
            '🃚' => ['#000000', "10", '🃚'],
            '🃛' => ['#000000', "11", '🃛'],
            '🃝' => ['#000000', "12", '🃝'],
            '🃞' => ['#000000', "13", '🃞'],
            '🃁' => ['#d3000e', "1", '🃁'],
            '🃂' => ['#d3000e', "2", '🃂'],
            '🃃' => ['#d3000e', "3", '🃃'],
            '🃄' => ['#d3000e', "4", '🃄'],
            '🃅' => ['#d3000e', "5", '🃅'],
            '🃆' => ['#d3000e', "6", '🃆'],
            '🃇' => ['#d3000e', "7", '🃇'],
            '🃈' => ['#d3000e', "8", '🃈'],
            '🃉' => ['#d3000e', "9", '🃉'],
            '🃊' => ['#d3000e', "10", '🃊'],
            '🃋' => ['#d3000e', "11", '🃋'],
            '🃍' => ['#d3000e', "12", '🃍'],
            '🃎' => ['#d3000e', "13", '🃎'],
        ];
    }

    /**
     * Draws cards from the deck.
     * 
     * @param array $cards The deck of cards to draw from.
     * @param array $drawnCards The array to store drawn cards.
     * 
     * @return array<string, mixed> The updated state after drawing cards.
     */
    public function drawCards($cards1 , $drawnCards)
    {
        
        if ($cards1 === null) {
            $this->shuffleDeck();
            $cards1 = $this->cards;
        }
        $sumValue = array_sum(array_column($drawnCards, 1));
        if (!empty($cards1) && $sumValue < 21) {
            $cardKey = array_key_first($cards1);
            $card = $cards1[$cardKey];
            unset($cards1[$cardKey]);
            array_push($drawnCards, $card);
        }

        $sumValue = array_sum(array_column($drawnCards, 1));

        return ['hand' => $drawnCards, 'cards' => $cards1, 'sumValue' => $sumValue];
    }

    /**
     * Shuffles the deck of cards.
     */
    private function shuffleDeck()
    {
        $keys = array_keys($this->cards);
        shuffle($keys);
        $shuffledDeck = [];
        foreach ($keys as $key) {
            $shuffledDeck[$key] = $this->cards[$key];
        }
        $this->cards = $shuffledDeck;
    }
}

/**
 * Class Card
 *
 * Represents a single playing card with its graphic, color, and value.
 */
class Card
{
    private $color;
    private $value;
    private $form;

    public function __construct($color, $value ,$form)
    {
        $this->color = $color;
        $this->value = $value;
        $this->form = $form;
    }

    /**
     * Get the color of the card.
     *
     * @return string The color of the card.
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Get the value of the card.
     *
     * @return string The value of the card.
     */
    public function getValue()
    {
        return $this->value;
    }
    /**
     * Get the form of the card.
     *
     * @return string The form of the card.
     */
    public function getform()
    {
        return $this->form;
    }
}
