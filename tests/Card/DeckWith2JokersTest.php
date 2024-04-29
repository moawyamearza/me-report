<?php

use PHPUnit\Framework\TestCase;
use App\Card\DeckWith2Jokers;

class DeckWith2JokersTest extends TestCase
{
    /**
     * Test if initEnglishDeck2Jokers returns an array with correct keys and values.
     */
    public function testInitEnglishDeck2Jokers()
    {
        $deck = new DeckWith2Jokers();
        $cards = $deck->initEnglishDeck2Jokers();

        // Assert that $cards is an array
        $this->assertIsArray($cards);

        // Assert that $cards contains the expected number of cards
        $this->assertCount(54, $cards);

        // Assert that each card has the correct format of key-value pair
        foreach ($cards as $card => $color) {
            $this->assertIsString($card);
            $this->assertIsString($color);
        }
    }

    /**
     * Test if shuffleCards returns a shuffled deck of cards.
     */
    public function testShuffleCards()
    {
        $deck = new DeckWith2Jokers();
        $shuffledCards = $deck->shuffleCards();

        // Assert that $shuffledCards is an array
        $this->assertIsArray($shuffledCards);

        // Assert that $shuffledCards contains the same number of cards as the original deck
        $this->assertCount(54, $shuffledCards);

        // Assert that $shuffledCards contains the same cards but in different order
        $originalDeck = $deck->initEnglishDeck2Jokers();
        $this->assertEquals($originalDeck, $shuffledCards);
    }
}
