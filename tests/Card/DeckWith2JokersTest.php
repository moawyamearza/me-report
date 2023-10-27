<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DeckWith2JokersTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testinitEnglishDeck2Jokers()
    {
        $die = new DeckWith2Jokers();
        $this->assertInstanceOf("\App\Card\DeckWith2Jokers", $die);

        $res = $die->initEnglishDeck2Jokers();
        $expectedCard = '🂡';  
        $expectedColor = '#000000';

        $this->assertArrayHasKey($expectedCard, $res);
    
        $this->assertEquals($expectedColor, $res[$expectedCard]);
    }
    public function testshuffleCards()
    {
        $die = new DeckWith2Jokers();
        $this->assertInstanceOf("\App\Card\DeckWith2Jokers", $die);

        $res = $die->shuffleCards();
        $res1 = $die->initEnglishDeck2Jokers();

        $this->assertEquals($res1, $res);
    }
}
