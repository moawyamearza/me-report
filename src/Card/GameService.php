<?php

namespace App\Card;

use App\Card\CardGraphic as CardG;

class GameService
{
    
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
