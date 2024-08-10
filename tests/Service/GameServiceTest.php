<?php

use PHPUnit\Framework\TestCase;
use App\Service\GameService;
use App\Card\CardGraphic;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use PHPUnit\Framework\MockObject\MockObject;

class GameServiceTest extends TestCase
{
    private const SESSION_DRAWN_CARDS = 'drawn_cards';
    private const SESSION_SUM_VALUE = 'sum_value';
    private const SESSION_CARDS = 'cards';
    private const SESSION_DRAWN_CARDS_BANK = 'drawn_cardsbank';
    private const SESSION_SUM_VALUE_BANK = 'sum_valuebank';
    private const SESSION_CARDS_BANK = 'cardsbank';

    /** @var GameService */
    private $gameService;

    /** @var MockObject|CardGraphic */
    private $cardGraphicMock;

    /** @var MockObject|SessionInterface */
    private $sessionMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cardGraphicMock = $this->createMock(CardGraphic::class);
        $this->sessionMock = $this->createMock(SessionInterface::class);
        $this->gameService = new GameService($this->cardGraphicMock);
    }

    public function testDrawCard(): void
    {
        // Arrange
        $drawnCards = [];
        $initialCards = [['color' => 'red', 'value' => '10', 'form' => 'heart']]; // Example data
        $result = [
            'hand' => [['color' => 'black', 'value' => '5', 'form' => 'spade']], // Example drawn card
            'cards' => [['color' => 'red', 'value' => '10', 'form' => 'heart']],
            'sumValue' => 15
        ];

        $this->sessionMock->expects($this->exactly(3))
            ->method('get')
            ->willReturnMap([
                [self::SESSION_DRAWN_CARDS, [], []], 
                [self::SESSION_CARDS, null, $initialCards],
                [self::SESSION_SUM_VALUE, 0, 0]
            ]);

        $this->cardGraphicMock->expects($this->once())
            ->method('drawCards')
            ->with($initialCards, $drawnCards)
            ->willReturn($result);

        $this->sessionMock->expects($this->exactly(3))
            ->method('set')
            ->withConsecutive(
                [self::SESSION_DRAWN_CARDS, $result['hand']],
                [self::SESSION_SUM_VALUE, $result['sumValue']],
                [self::SESSION_CARDS, $result['cards']]
            );

        // Act
        $this->gameService->drawCard($this->sessionMock);
    }

    public function testDrawBankCards(): void
    {
        // Arrange
        $drawnCardsBank = [];
        $initialCardsBank = [['color' => 'black', 'value' => '5', 'form' => 'spade']]; 
        $resultBank = [
            'hand' => [['color' => 'red', 'value' => '10', 'form' => 'heart']], 
            'cardsbank' => [['color' => 'black', 'value' => '5', 'form' => 'spade']],
            'sumValue' => 17
        ];

        $this->sessionMock->expects($this->exactly(3))
            ->method('get')
            ->willReturnMap([
                [self::SESSION_DRAWN_CARDS_BANK, [], []], 
                [self::SESSION_CARDS_BANK, null, $initialCardsBank],
                [self::SESSION_SUM_VALUE_BANK, 0, 0]
            ]);

        $this->cardGraphicMock->expects($this->once())
            ->method('drawCardsbank')
            ->with($initialCardsBank, $drawnCardsBank)
            ->willReturn($resultBank);

        $this->sessionMock->expects($this->exactly(3))
            ->method('set')
            ->withConsecutive(
                [self::SESSION_DRAWN_CARDS_BANK, $resultBank['hand']],
                [self::SESSION_SUM_VALUE_BANK, $resultBank['sumValue']],
                [self::SESSION_CARDS_BANK, $resultBank['cardsbank']]
            );

        // Act
        $this->gameService->drawBankCards($this->sessionMock);
    }
}
