<?php

namespace App\Card;

use App\Card\CardGraphic as CardG;

class GameService
{
    /**
     * Draw cards from the deck.
     * 
     * @param array<string, arry{string,string}> $cards The current cards in hand.
     * @param array<string, string> $new The new cards drawn.
     * @param array<int> $arrcardvalue Array containing values of the cards.
     * @param int $valueSum The sum of card values.
     * @return array<string, mixed> The updated state after drawing cards.
     */
    public function draw($cards, $new, $arrcardvalue, $valueSum)
    {
        if (!empty($cards) && $valueSum <= 21) {
            $cardgrafic = array_key_first($cards);
            $arrvalue = reset($cards);
            $color = $arrvalue[0];
            $cardvalue = $arrvalue[1];
            $new[$cardgrafic] = $color;
            array_push($arrcardvalue, $cardvalue);
            $valueSum = array_sum($arrcardvalue);
            unset($cards[$cardgrafic]);
        }

        return ['cards' => $cards, 'new' => $new, 'valueSum' => $valueSum , 'arrcardvalue' => $arrcardvalue];
    }
}
