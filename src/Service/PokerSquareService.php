<?php

namespace App\Service;

use App\Card\Card;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\CardGraphic as CardG;

class PokerSquareService
{
    public const SESSION_DRAWN_CARDS = 'drawn_cards';
    public const SESSION_LASTCARD = 'lastcard';
    public const SESSION_CARDS = 'cards';
    public const SESSION_POKER_SQUARE = 'poker_square';


    private CardG $cardGraphic;
    private int $size; 

    public function __construct(CardG $cardGraphic, int $size = 5) 
    {
        $this->cardGraphic = $cardGraphic;
        $this->size = $size; // Set size
    }

    public function drawCardsPocker(SessionInterface $session): void
    {
        $drawnCards = $session->get(self::SESSION_DRAWN_CARDS, []);
        $cards = $session->get(self::SESSION_CARDS, null);
        $lastcard = $session->get(self::SESSION_LASTCARD, 0);

        $result = $this->cardGraphic->drawCardsPocker($cards, $drawnCards);
        $session->set(self::SESSION_DRAWN_CARDS, $result['hand']);
        $session->set(self::SESSION_LASTCARD, $result['lastcard']);
        $session->set(self::SESSION_CARDS, $result['cards']);
    }
    
    /**
     * Places a card in the specified row and column in the grid.
     */
    public function placeCard(SessionInterface $session, int $row, int $col): void
    {
        $grid = $session->get(self::SESSION_POKER_SQUARE, array_fill(0, $this->size * $this->size, null));
        $card = $session->get(self::SESSION_LASTCARD, 0);
        $index = $row * $this->size + $col;
        if ($card !== null && $grid[$index] === null) {
            $grid[$index] = $card;
            $session->set(self::SESSION_POKER_SQUARE, $grid);
        }
    }

    public function calculateScore()
    {
        $score = 0;
        // Beräkna poäng för varje rad och kolumn
        for ($i = 0; $i < $this->size; $i++) {
            $row = array_column($this->grid, $i);
            $col = $this->grid[$i];
            $score += $this->evaluateHand($row);
            $score += $this->evaluateHand($col);
        }
        return $score;
    }
}
