<?php

namespace App\Tests\Service;

use App\Service\PokerSquareService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\CardGraphic as CardG;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PokerSquareServiceTest extends TestCase
{
    private PokerSquareService $pokerSquareService;
    private MockObject|SessionInterface $sessionMock;
    private MockObject|CardG $cardGraphicMock;

    protected function setUp(): void
    {
        $this->cardGraphicMock = $this->createMock(CardG::class);
        $this->pokerSquareService = new PokerSquareService($this->cardGraphicMock, 5);
        $this->sessionMock = $this->createMock(SessionInterface::class);
        
    }

    public function testDrawCardsPocker(): void
    {
        $drawnCards = ['card1', 'card2'];
        $cards = ['card3', 'card4'];
        $lastCard = 'card5';
        $result = [
            'hand' => $drawnCards,
            'lastcard' => $lastCard,
            'cards' => $cards,
        ];

        $this->cardGraphicMock
            ->expects($this->once())
            ->method('drawCardsPocker')
            ->with(null, [])
            ->willReturn($result);

        $this->sessionMock
            ->expects($this->exactly(3))
            ->method('get')
            ->withConsecutive(
                [PokerSquareService::SESSION_DRAWN_CARDS, []],
                [PokerSquareService::SESSION_CARDS, null],
                [PokerSquareService::SESSION_LASTCARD, 0]
            )
            ->willReturnOnConsecutiveCalls([], null, 0);

        $this->sessionMock
            ->expects($this->exactly(3))
            ->method('set')
            ->withConsecutive(
                [PokerSquareService::SESSION_DRAWN_CARDS, $drawnCards],
                [PokerSquareService::SESSION_LASTCARD, $lastCard],
                [PokerSquareService::SESSION_CARDS, $cards]
            );

        $this->pokerSquareService->drawCardsPocker($this->sessionMock);
    }
    
   
    public function testCalculateScores(): void
    {
        $grid = [
            ['card' => 1, 'suit' => 'hearts'],
            ['card' => 2, 'suit' => 'hearts'],
            ['card' => 3, 'suit' => 'hearts'],
            ['card' => 4, 'suit' => 'hearts'],
            ['card' => 5, 'suit' => 'hearts'],
            // More cards...
        ];

        $this->sessionMock
            ->expects($this->once())
            ->method('get')
            ->with(PokerSquareService::SESSION_POKER_SQUARE, array_fill(0, 25, null))
            ->willReturn($grid);

        $this->pokerSquareService->calculateScores($this->sessionMock);

        // Verify the scores calculated based on the grid
        // Add assertions here based on expected scores.
    }

    public function testSparaScore(): void
    {
        $this->sessionMock
            ->expects($this->once())
            ->method('get')
            ->with('scores', [])
            ->willReturn([]);

        $this->sessionMock
            ->expects($this->once())
            ->method('set')
            ->with('scores', [['name' => 'Player1', 'score' => 100]]);

        $this->pokerSquareService->sparaScore($this->sessionMock, 100, 'Player1');
    }

    public function testGetTopScores(): void
    {
        $scores = [
            ['name' => 'Player1', 'score' => 100],
            ['name' => 'Player2', 'score' => 90],
        ];

        $this->sessionMock
            ->expects($this->once())
            ->method('get')
            ->with('scores', [])
            ->willReturn($scores);

        $result = $this->pokerSquareService->getTopScores($this->sessionMock);
        $this->assertEquals($scores, $result);
    }

    public function testIsGridFull(): void
    {
        $grid = array_fill(0, 25, 1); // All cells filled
        $this->sessionMock
            ->expects($this->once())
            ->method('get')
            ->with(PokerSquareService::SESSION_POKER_SQUARE, array_fill(0, 25, null))
            ->willReturn($grid);

        $result = $this->pokerSquareService->isGridFull($this->sessionMock);
        $this->assertTrue($result);
    }

    public function testNoCardToPlace(): void
    {
        // Expect get calls
        $this->sessionMock->expects($this->exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                array_fill(0, 25, null), // Grid initially empty
                0 // No card
            );

        // Expect no set call
        $this->sessionMock->expects($this->never())
            ->method('set');

        // Mock drawCardsPocker method
        $this->cardGraphicMock->expects($this->never())
            ->method('drawCardsPocker');

        // Execute method
        $this->pokerSquareService->placeCard($this->sessionMock, 2, 2);
    }


}
