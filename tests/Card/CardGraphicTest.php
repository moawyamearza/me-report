<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class CardGraphicTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testInitEnglishDeck()
    {
        $die = new CardGraphic();
        $this->assertInstanceOf("\App\Card\CardGraphic", $die);

        $res = $die->initEnglishDeck();
        $expectedCard = '🂡';  
        $expectedColor = '#000000';

        $this->assertArrayHasKey($expectedCard, $res);
    
        $this->assertEquals($expectedColor, $res[$expectedCard]);
    }
    public function testshuffleCards()
    {
        $die = new CardGraphic();
        $this->assertInstanceOf("\App\Card\CardGraphic", $die);

        $res = $die->shuffleCards();
        $this->assertNotEquals($die->initEnglishDeck(), $res);

    }
}
