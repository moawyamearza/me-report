<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PokerSquareService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
            $session->remove('drawn_cards');
            $session->remove('lastcard');
            $session->remove('button_type');
            $session->remove('poker_square');
            $session->remove('game_over');

            $session->set(self::SESSION_BUTTON_TYPE, 'submit');
        }

        if ($request->request->get('draw')) {
            $this->pokerService->drawCardsPocker($session);
            $session->set(self::SESSION_BUTTON_TYPE, 'hidden');
        } elseif ($request->request->has('placeCard')) {
            $index = $request->request->get('placeCard');
            $index = (int) $index;
            $row = (int) floor($index / 5); 
            $col = (int) ($index % 5); 
            $this->pokerService->placeCard($session, $row, $col);

            if ($this->pokerService->isGridFull($session)) {
                $session->set('game_over', true);
            }
        }

        $scores = $this->pokerService->calculateScores($session);

        return $this->render('game1/index.html.twig', [
            'poker_square' => $session->get(self::SESSION_POKER_SQUARE, array_fill(0, 25, null)),
            'new' => $session->get(self::SESSION_DRAWN_CARDS, []),
            'lastcard' => $session->get(self::SESSION_LASTCARD, null),
            'type' => $session->get(self::SESSION_BUTTON_TYPE, 'submit'),
            'scores' => $scores,
            'size' => 5,
            'game_over' => $session->get('game_over', false),
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4, 'cards1' => 5]),
        ]);
    }

    /**
     * @Route("/proj/end-game", name="end_game", methods={"POST"})
     */
    public function endGame(Request $request, SessionInterface $session, PokerSquareService $pokerSquareService): Response
    {
        $name = (string) $request->request->get('name');
        $finalScore = $pokerSquareService->calculateScores($session);
        $score = (int) $finalScore['totalScore'];
        $pokerSquareService->sparaScore($session, $score, $name);
        $session->remove('drawn_cards');
        $session->remove('lastcard');
        $session->remove('cards');
        $session->remove('poker_square');
        $session->remove('game_over');

        return $this->redirectToRoute('show_score');
    }

    /**
     * @Route("/proj/show_score", name="show_score")
     */
    public function show_score(Request $request, SessionInterface $session): Response
    {
        $scores = $session->get('scores', []);
        return $this->render('game1/toplist.html.twig', [
            'scores' => $scores,
        ]);
    }

    /**
     * @Route("/proj/show_regle", name="show_regle")
     */
    public function show_regle(Request $request, SessionInterface $session): Response
    {
        $scores = $session->get('scores', []);
        return $this->render('game1/regle.html.twig', [
            'scores' => $scores,
        ]);
    }

    /**
     * @Route("/proj/about", name="about_proj")
     */
    public function about(): Response
    {
        return $this->render('game1/about.html.twig');
    }
}
