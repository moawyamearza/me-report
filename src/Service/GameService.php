<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\CardGraphic as CardG;

class GameService
{
    private const SESSION_DRAWN_CARDS = 'drawn_cards';
    private const SESSION_SUM_VALUE = 'sum_value';
    private const SESSION_CARDS = 'cards';
    private const SESSION_DRAWN_CARDS_BANK = 'drawn_cardsbank';
    private const SESSION_SUM_VALUE_BANK = 'sum_valuebank';
    private const SESSION_CARDS_BANK = 'cardsbank';

    private CardG $cardGraphic;

    public function __construct(CardG $cardGraphic)
    {
        $this->cardGraphic = $cardGraphic;
    }

    public function drawCard(SessionInterface $session): void
    {
        $drawnCards = $session->get(self::SESSION_DRAWN_CARDS, []);
        $cards = $session->get(self::SESSION_CARDS, null);
        $sumValue = $session->get(self::SESSION_SUM_VALUE, 0);

        $result = $this->cardGraphic->drawCards($cards, $drawnCards);
        $session->set(self::SESSION_DRAWN_CARDS, $result['hand']);
        $session->set(self::SESSION_SUM_VALUE, $result['sumValue']);
        $session->set(self::SESSION_CARDS, $result['cards']);
    }

    public function drawBankCards(SessionInterface $session): void
    {
        $drawnCardsBank = $session->get(self::SESSION_DRAWN_CARDS_BANK, []);
        $cardsBank = $session->get(self::SESSION_CARDS_BANK, null);
        $sumValueBank = $session->get(self::SESSION_SUM_VALUE_BANK, 0);


        $resultBank = $this->cardGraphic->drawCardsbank($cardsBank, $drawnCardsBank);
        $session->set(self::SESSION_DRAWN_CARDS_BANK, $resultBank['hand']);
        $session->set(self::SESSION_SUM_VALUE_BANK, $resultBank['sumValue']);
        $session->set(self::SESSION_CARDS_BANK, $resultBank['cardsbank']);

    }
}
