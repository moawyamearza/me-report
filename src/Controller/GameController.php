<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\GameService;

class GameController extends AbstractController
{
    private const SESSION_DRAWN_CARDS = 'drawn_cards';
    private const SESSION_SUM_VALUE = 'sum_value';
    private const SESSION_DRAWN_CARDS_BANK = 'drawn_cardsbank';
    private const SESSION_SUM_VALUE_BANK = 'sum_valuebank';
    private const SESSION_GAME_FINISHED = 'game_finished';

    private GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
    * @Route("/Game", name="game", methods={"GET","HEAD","POST"})
    */
    public function start(Request $request, SessionInterface $session): Response
    {
        if ($request->request->get('start')) {
            $session->clear();
            return $this->redirectToRoute('Startgame');
        } elseif ($request->request->get('dokumentation')) {
            return $this->redirectToRoute('doc');
        }
        return $this->render('Game/landdningssida.html.twig');
    }

    /**
     * @Route("/Game/start", name="Startgame", methods={"GET","HEAD","POST"})
     */
    public function gameProcess(Request $request, SessionInterface $session): Response
    {
        if ($request->request->get('newround')) {
            $session->clear();
        }

        if ($request->request->get('draw')) {
            $this->gameService->drawCard($session);
        } elseif ($request->request->get('stop')) {
            for ($i = 0; $i < 100; $i++) {
                $this->gameService->drawBankCards($session);
                $session->set(self::SESSION_GAME_FINISHED, true);
            }
        }

        return $this->render('Game/game.html.twig', [
            'new' => $session->get(self::SESSION_DRAWN_CARDS, []),
            'valuesum' => $session->get(self::SESSION_SUM_VALUE, 0),
            'newbank' => $session->get(self::SESSION_DRAWN_CARDS_BANK, []),
            'valuesumBanken' => $session->get(self::SESSION_SUM_VALUE_BANK, 0),
            'gamefinshed' => $session->get(self::SESSION_GAME_FINISHED, false),
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4, 'cards1' => 5]),
        ]);
    }

    /**
    * @Route("/Game/doc", name="doc", methods={"GET","HEAD","POST"})
    */
    public function doc(): Response
    {
        return $this->render('Game/doc.html.twig');
    }

    /**
     * @Route("/api/game", name="api_game", methods={"GET","HEAD","POST"})
     */
    public function getGameStateJson(SessionInterface $session): JsonResponse
    {
        $gameState = [
            'new' => $session->get(self::SESSION_DRAWN_CARDS, []),
            'newBanken' => $session->get(self::SESSION_DRAWN_CARDS_BANK, []),
            'valueSum' => $session->get(self::SESSION_SUM_VALUE, 0),
            'valueSumBanken' => $session->get(self::SESSION_SUM_VALUE_BANK, 0),
        ];
        return new JsonResponse($gameState);
    }
}
