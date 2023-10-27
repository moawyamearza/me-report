<?php

namespace App\Card;

use App\Card\GameService;
use PHPUnit\Framework\TestCase;

class GameServiceTest extends TestCase
{
    public function testDraw()
    {
        // Create an instance of the GameService
        $gameService = new GameService();

        // Define the input data
        $cards = [
            '🂡' => ['#000000', 1],
            '🂢' => ['#000000', 2],
            // Add more cards as needed
        ];
        $new = [];
        $arrcardvalue = [];
        $valueSum = 0;

        // Call the draw method
        $result = $gameService->draw($cards, $new, $arrcardvalue, $valueSum);

        // Assert that the result is an array with specific keys
        $this->assertArrayHasKey('cards', $result);
        $this->assertArrayHasKey('new', $result);
        $this->assertArrayHasKey('valueSum', $result);
        $this->assertArrayHasKey('arrcardvalue', $result);

        // You can add more specific assertions based on your business logic.
        // For example, you can check if the cards array is updated as expected,
        // if the new card is added to the "new" array, etc.
    }
}
