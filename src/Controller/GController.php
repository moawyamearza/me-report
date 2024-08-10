<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PokerSquareService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Card;

class GController extends AbstractController
{
    private const SESSION_DRAWN_CARDS = 'drawn_cards';
    private const SESSION_LASTCARD = 'lastcard';
    private const SESSION_POKER_SQUARE = 'poker_square';
    private const SESSION_BUTTON_TYPE = 'button_type';

    private PokerSquareService $pokerService;

    public function __construct(PokerSquareService $pokerService)
    {
        $this->pokerService = $pokerService;
    }

    /**
     * @Route("/proj", name="game_index")
     */
    public function index(Request $request, SessionInterface $session): Response
    {
        if ($request->request->get('newround')) {
            $session->clear();
            $session->set(self::SESSION_BUTTON_TYPE, 'submit');
        }
        if ($request->request->get('draw')) {
            $this->pokerService->drawCardsPocker($session);
            $session->set(self::SESSION_BUTTON_TYPE, 'hidden');

        } elseif ($request->request->has('placeCard')) {
            $index = $request->request->get('placeCard');
            $row = floor($index / 5);
            $col = $index % 5;
            $this->pokerService->placeCard($session, $row, $col);
            $this->pokerService->drawCardsPocker($session);

        }

        return $this->render('game1/index.html.twig', [
            'poker_square' => $session->get(self::SESSION_POKER_SQUARE, array_fill(0, 25, null)),
            'new' => $session->get(self::SESSION_DRAWN_CARDS, []),
            'lastcard' => $session->get(self::SESSION_LASTCARD, null),
            'type' => $session->get(self::SESSION_BUTTON_TYPE, 'submit'),
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4, 'cards1' => 5]),
        ]);
    }
}
