<?php

namespace Tests\Unit\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardGraphic;
use App\Card\Card;

class CardGraphicTest extends TestCase
{
    /** @var CardGraphic */
    private $cardGraphic;
    private $card;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cardGraphic = new CardGraphic();
        $this->card = new Card('red', '10', 'heart');

    }

        public function testDrawPlayerCards()
    {
        $drawnCards = [];
        $initialState = $this->cardGraphic->drawCards($drawnCards, []);

        $this->assertIsArray($initialState);
        $this->assertArrayHasKey('hand', $initialState);
        $this->assertArrayHasKey('cards', $initialState);
        $this->assertArrayHasKey('sumValue', $initialState);
    }

    public function testDrawCards(): void
    {
        $initialDeck = $this->cardGraphic->drawCards(null, []);
        $this->assertCount(51, $initialDeck['cards']); // One card is drawn

        $drawnCards = [];
        $updatedState = $this->cardGraphic->drawCards($initialDeck['cards'], $drawnCards);
        $this->assertLessThan(21, $updatedState['sumValue']);

    }

    public function testDrawCardsbank(): void
    {
        $initialDeck = $this->cardGraphic->drawCardsbank(null, []);
        $this->assertCount(51, $initialDeck['cardsbank']); // One card is drawn

        $drawnCards = [];
        $updatedState = $this->cardGraphic->drawCardsbank($initialDeck['cardsbank'], $drawnCards);
        $this->assertLessThan(19, $updatedState['sumValue']);
    }

    public function testGetColor(): void
    {
        $this->assertSame('red', $this->card->getColor());
    }

    public function testGetValue(): void
    {
        $this->assertSame('10', $this->card->getValue());
    }

    public function testGetForm(): void
    {
        $this->assertSame('heart', $this->card->getForm());
    }
}
