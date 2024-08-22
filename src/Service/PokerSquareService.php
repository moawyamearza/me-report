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
        $this->size = $size;
    }

    public function drawCardsPocker(SessionInterface $session): void
    {
        $drawnCards = $session->get(self::SESSION_DRAWN_CARDS, []);
        $cards = $session->get(self::SESSION_CARDS, null);
        $lastcard = $session->get(self::SESSION_LASTCARD, 0);

        if (!is_array($drawnCards)) {
            $drawnCards = [];
        }
        if (!is_array($cards) && !is_null($cards)) {
            $cards = null;
        }

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
        
        $grid = array_fill(0, $this->size * $this->size, null);
        
        $card = $session->get(self::SESSION_LASTCARD, 0);
        $index = $row * $this->size + $col;
        if ($card !== null && $grid[$index] === null) {
            $grid[$index] = $card;
            $session->set(self::SESSION_POKER_SQUARE, $grid);
            $this->drawCardsPocker($session);
        }
    }


    /**
     * @return array<string, array<int>|int>
     */
    public function calculateScores(SessionInterface $session): array
    {
        /** @var array<int|null> $grid */
        $grid = $session->get(self::SESSION_POKER_SQUARE, array_fill(0, $this->size * $this->size, null));
        if (!is_array($grid)) {
            $grid = array_fill(0, $this->size * $this->size, null);
        }

        /** @var array<int> $rowScores */
        $rowScores = [];
        /** @var array<int> $colScores */
        $colScores = [];

        $totalScore = 0;

        for ($i = 0; $i < $this->size; $i++) {
            /** @var array<int|null> $row */
            $row = array_slice($grid, $i * $this->size, $this->size);
            /** @var array<int|null> $col */
            $col = array_column(array_chunk($grid, $this->size), $i);

            $rowScore = $this->calculateHandScore($row);
            $colScore = $this->calculateHandScore($col);

            $rowScores[] = $rowScore;
            $colScores[] = $colScore;

            $totalScore += $rowScore + $colScore;
        }

        return [
            'rowScores' => $rowScores,
            'colScores' => $colScores,
            'totalScore' => $totalScore,
        ];
    }

    /**
     * @param array<int|null> $hand
     */
    private function calculateHandScore(array $hand): int
    {
        if ($this->isRoyalFlush($hand)) {
            return 100;
        }
        if ($this->isStraightFlush($hand)) {
            return 75;
        }
        if ($this->isFourOfAKind($hand)) {
            return 50;
        }
        if ($this->isFullHouse($hand)) {
            return 25;
        }
        if ($this->isFlush($hand)) {
            return 20;
        }
        if ($this->isStraight($hand)) {
            return 15;
        }
        if ($this->isThreeOfAKind($hand)) {
            return 10;
        }
        if ($this->isTwoPair($hand)) {
            return 5;
        }
        if ($this->isOnePair($hand)) {
            return 2;
        }

        return 0;
    }

    /**
     * @param array<int|null> $hand
     */
    private function isRoyalFlush(array $hand): bool
    {
        return $this->isStraightFlush($hand) && $this->containsValues($hand, ["10", "11", "12", "13", "1"]);
    }

    /**
     * @param array<int|null> $hand
     */
    private function isStraightFlush(array $hand): bool
    {
        return $this->isFlush($hand) && $this->isStraight($hand);
    }

    /**
     * @param array<int|null> $hand
     */
    private function isFourOfAKind(array $hand): bool
    {
        return $this->hasOfAKind($hand, 4);
    }

    /**
     * @param array<int|null> $hand
     */
    private function isFullHouse(array $hand): bool
    {
        return $this->hasOfAKind($hand, 3) && $this->hasOfAKind($hand, 2);
    }

    /**
     * @param array<int|null> $hand
     */
    private function isFlush(array $hand): bool
    {
        $suits = array_column($hand, 3);
        return count(array_unique($suits)) === 1;
    }

    /**
     * @param array<int|null> $hand
     */
    private function isStraight(array $hand): bool
    {
        $values = array_map('intval', array_column($hand, 1));
        
        $values = array_unique(array_filter($values, fn($value) => $value > 0));
        
        sort($values);

        if (count($values) < 5) {
            return false;
        }

        if (array_intersect([1, 10, 11, 12, 13], $values) === [1, 10, 11, 12, 13]) {
            return true;
        }

        $numValues = count($values);
        for ($i = 0; $i <= $numValues - 5; $i++) {
            $sequence = array_slice($values, $i, 5);
            if (end($sequence) - reset($sequence) === 4) {
                return true;
            }
        }

        return false;
    }




    /**
     * @param array<int|null> $hand
     */
    private function isThreeOfAKind(array $hand): bool
    {
        return $this->hasOfAKind($hand, 3);
    }

    /**
     * @param array<int|null> $hand
     */
    private function isTwoPair(array $hand): bool
    {
        $valueCounts = array_count_values(array_column($hand, 1));
        return count(array_filter($valueCounts, fn($count) => $count === 2)) === 2;
    }

    /**
     * @param array<int|null> $hand
     */
    private function isOnePair(array $hand): bool
    {
        return $this->hasOfAKind($hand, 2);
    }

    /**
     * @param array<int|null> $hand
     */
    private function hasOfAKind(array $hand, int $count): bool
    {
        $valueCounts = array_count_values(array_column($hand, 1));
        return in_array($count, $valueCounts);
    }

    /**
     * @param array<int|null> $hand
     * @param array<string> $neededValues
     */
    private function containsValues(array $hand, array $neededValues): bool
    {
        $values = array_column($hand, 1);
        return !array_diff($neededValues, $values);
    }

    public function sparaScore(SessionInterface $session, int $score, string $name): void
    {
        /** @var array<int, array{name: string, score: int}> $scores */
        $scores = $session->get('scores', []);
        if (!is_array($scores)) {
            $scores = [];
        }

        $scores[] = ['name' => $name, 'score' => $score];

        usort($scores, fn($a, $b) => $b['score'] - $a['score']);

        $scores = array_slice($scores, 0, 10);

        $session->set('scores', $scores);
    }

    /**
     * @return array<int, array{name: string, score: int}>
     */
    public function getTopScores(SessionInterface $session): array
    {
        /** @var array<int, array{name: string, score: int}> $scores */
        $scores = $session->get('scores', []);
        if (!is_array($scores)) {
            $scores = [];
        }
        return $scores;
    }

    public function isGridFull(SessionInterface $session): bool
    {
        /** @var array<int|null> $grid */
        $grid = $session->get(self::SESSION_POKER_SQUARE, array_fill(0, $this->size * $this->size, null));
        if (!is_array($grid)) {
            $grid = array_fill(0, $this->size * $this->size, null);
        }
        foreach ($grid as $card) {
            if ($card === null) {
                return false;
            }
        }
        return true;
    }
}
