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
        parent::setUp();
        $this->cardGraphicMock = $this->createMock(CardG::class);
        $this->pokerSquareService = new PokerSquareService($this->cardGraphicMock, 5);
        $this->sessionMock = $this->createMock(SessionInterface::class);
        
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(PokerSquareService::class, $this->pokerSquareService);
        $this->assertNotNull($this->pokerSquareService);
    }

    public function testInitialization(): void
    {
        $reflection = new \ReflectionClass($this->pokerSquareService);
        
        $cardGraphicProperty = $reflection->getProperty('cardGraphic');
        $cardGraphicProperty->setAccessible(true);
        $this->assertSame($this->cardGraphicMock, $cardGraphicProperty->getValue($this->pokerSquareService));
        
        $sizeProperty = $reflection->getProperty('size');
        $sizeProperty->setAccessible(true);
        $this->assertEquals(5, $sizeProperty->getValue($this->pokerSquareService));
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

        $grid = array_fill(0, 25, null);

        $grid[0] = [null, "10", null, "Hearts"];
        $grid[1] = [null, "11", null, "Hearts"];
        $grid[2] = [null, "12", null, "Hearts"];
        $grid[3] = [null, "13", null, "Hearts"];
        $grid[4] = [null, "1", null, "Hearts"];

        $grid[5] = [null, "4", null, "Diamonds"];
        $grid[6] = [null, "5", null, "Diamonds"];
        $grid[7] = [null, "6", null, "Diamonds"];
        $grid[8] = [null, "7", null, "Diamonds"];
        $grid[9] = [null, "8", null, "Diamonds"];
        
        $grid[10] = [null, "9", null, "Clubs"];
        $grid[11] = [null, "9", null, "Hearts"];
        $grid[12] = [null, "9", null, "Spades"];
        $grid[13] = [null, "9", null, "Diamonds"];
        $grid[14] = [null, "10", null, "Clubs"];

        $grid[15] = [null, "2", null, "Spades"];
        $grid[16] = [null, "2", null, "Clubs"];
        $grid[17] = [null, "2", null, "Diamonds"];
        $grid[18] = [null, "3", null, "Hearts"];
        $grid[19] = [null, "3", null, "Spades"];
        
        $grid[20] = [null, "5", null, "Hearts"];
        $grid[21] = [null, "6", null, "Hearts"];
        $grid[22] = [null, "7", null, "Hearts"];
        $grid[23] = [null, "8", null, "Hearts"];
        $grid[24] = [null, "2", null, "Hearts"];
        
        $this->sessionMock
            ->expects($this->once())
            ->method('get')
            ->with(PokerSquareService::SESSION_POKER_SQUARE, array_fill(0, 25, null))
            ->willReturn($grid);

        $expectedResult = [
            'rowScores' => [100, 75, 50, 25, 20],
            'colScores' => [0, 0, 0, 0, 0], 
            'totalScore' => 100 + 75 + 50 + 25 + 20
        ];
        
        $result = $this->pokerSquareService->calculateScores($this->sessionMock);
        
        $this->assertEquals($expectedResult, $result);
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
    
    public function testIsGridFullWhenGridIsNotFull(): void
    {
        $grid = array_fill(0, 25, null); // All cells empty
        $grid[0] = 1; // One cell filled
        $this->sessionMock
            ->expects($this->once())
            ->method('get')
            ->with(PokerSquareService::SESSION_POKER_SQUARE, array_fill(0, 25, null))
            ->willReturn($grid);
    
        $result = $this->pokerSquareService->isGridFull($this->sessionMock);
        $this->assertFalse($result);
    }

    private function createHand(array $cards): array
    {
        return array_map(fn($card) => ['card' => $card['value'], 'suit' => $card['suit']], $cards);
    }

    public function testCalculateScoresWithEmptyGrid(): void
    {
        $emptyGrid = array_fill(0, 25, null);

        $this->sessionMock
            ->expects($this->once())
            ->method('get')
            ->with(PokerSquareService::SESSION_POKER_SQUARE, array_fill(0, 25, null))
            ->willReturn($emptyGrid);

        $expectedResult = [
            'rowScores' => array_fill(0, 5, 0),
            'colScores' => array_fill(0, 5, 0),
            'totalScore' => 0
        ];

        $result = $this->pokerSquareService->calculateScores($this->sessionMock);

        $this->assertEquals($expectedResult, $result);
    }

    public function testCalculateScoresWithInvalidGridData(): void
    {
        $invalidGrid = ['invalid', 'data', 'here']; // Non-grid data

        $this->sessionMock
            ->expects($this->once())
            ->method('get')
            ->with(PokerSquareService::SESSION_POKER_SQUARE, array_fill(0, 25, null))
            ->willReturn($invalidGrid);

        $expectedResult = [
            'rowScores' => array_fill(0, 5, 0),
            'colScores' => array_fill(0, 5, 0),
            'totalScore' => 0
        ];

        $result = $this->pokerSquareService->calculateScores($this->sessionMock);

        $this->assertEquals($expectedResult, $result);
    }

   
}
