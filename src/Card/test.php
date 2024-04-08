<?php

// Include the CardGraphic class file
require_once './CardGraphic.php';

// Instantiate the CardGraphic object
$cardGraphic = new App\Card\CardGraphic();

// Call the drawCards() method
for ($i = 0; $i < 3; $i++) {
$result = $cardGraphic->drawCards();
}   
// Print the result
echo "Drawn Cards:\n";
foreach ($result['hand'] as $card) {
    echo "Color: {$card[0]}, Value: {$card[1]}, Form: {$card[2]}\n";
}
echo "Total Value Sum: {$result['valueSum']}\n";
