<?php

namespace App\Card;

/**
 * Class CardGraphic
 *
 * Represents a deck of playing cards with graphics and colors.
 */
class CardGraphic
{
    private $cards; // Array representing the deck of cards
    private $drawnCards; // Array to store all drawn cards

    public function __construct()
    {
        // Initialize the deck of cards
        $this->initializeDeck();
        $this->drawnCards = [];
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
     * @return array<string, mixed> The updated state after drawing cards.
     */
    public function drawCards()
    {
        $this->shuffleDeck();
        if (!empty($this->cards)) {
            $cardKey = array_key_first($this->cards);
            $card = $this->cards[$cardKey];
            unset($this->cards[$cardKey]);

            // Store the drawn card in the history
            array_push($this->drawnCards, $card);
        }

        // Calculate the sum of card values
        $valueSum = 0;
        foreach ($this->drawnCards as $card) {
            $valueSum += $card->getValue();
        }

        return ['hand' => $this->drawnCards, 'valueSum' => $valueSum];
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
