<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\CardGraphic as CardG;

class GameController extends AbstractController
{
    /**
    * @Route(
    *       "/Game",
    *       name="game",
    *       methods={"GET","HEAD","post"}
    * )
    */
    public function start(
        Request $request,
        SessionInterface $session
    ): Response {
        $start  = $request->request->get('start');
        $doc  = $request->request->get('dokumentation');
        if ($start) {
            $session->clear();
            return $this->redirectToRoute('Startgame');
        } elseif ($doc) {
            return $this->redirectToRoute('doc');
        }
        return $this->render('Game/landdningssida.html.twig');
    }
      /**
     * @Route(
     *       "/Game/start",
     *       name="Startgame",
     *       methods={"GET","HEAD","POST"}
     * )
     */
    public function gamepross(
        Request $request,
        SessionInterface $session,
    ): Response {
        $newround = $request->request->get('newround');
        $draw  = $request->request->get('draw');
        $stop = $request->request->get('stop');
        if ($newround) {
            $session->clear();
        }
        $cardGraphic = new CardG();
        $drawnCards = $session->get('drawn_cards', []);
        $cards = $session->get('cards', null);
        $sumValue = $session->get('sum_value', 0);
        $drawnCardsbsnk = $session->get('drawn_cardsbank', []);
        $cardsbank = $session->get('cardsbank', null);
        $sumValuebank = $session->get('sum_valuebank', 0);
        $gamefinshed = False;
        if ($draw) {
            $result = $cardGraphic->drawCards($cards,$drawnCards);
            $drawnCards = $result['hand'];
            $sumValue = $result['sumValue'];
            $cards = $result['cards'];
            $session->set('drawn_cards', $drawnCards);
            $session->set('sum_value', $sumValue);
            $session->set('cards', $cards);
        } elseif ($stop) {
            for ($i = 1; $i <= 100; $i++) {
                $resultbank = $cardGraphic->drawCardsbank($cardsbank,$drawnCardsbsnk);
                $drawnCardsbsnk = $resultbank['hand'];
                $sumValuebank = $resultbank['sumValue'];
                $cardsbank = $resultbank['cards'];
                $session->set('drawn_cardsbank', $drawnCardsbsnk);
                $session->set('sum_valuebank', $sumValuebank);
                $session->set('cardsbank', $cardsbank);
            }
            $gamefinshed = True;
        }
        $data = [
            'new' => $drawnCards,
            'valuesum' => $sumValue,
            'newbank' => $drawnCardsbsnk,
            'valuesumBanken' => $sumValuebank,
            'gamefinshed' => $gamefinshed,
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5,]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4,'cards1' => 5,]),
        ];
        return $this->render('Game/game.html.twig', $data);
    }
     /**
     * @Route(
     *       "/Game/doc",
     *       name="doc",
     *       methods={"GET","HEAD","post"}
     * )
     */
    public function doc(
        Request $request,
        SessionInterface $session
    ): Response {
        return $this->render('Game/doc.html.twig');
    }
    /**
     * @Route(
     *       "Game/api/game",
     *       name="api",
     *       methods={"GET","HEAD","post"}
     * )
     */
    public function getGameState(
        SessionInterface $session
    ): Response {
        $gameState = [
            'new' => $session->get("new") ?? array(),
            'newBanken' => $session->get("newBanken") ?? array(),
            'valueSum' => $session->get("valueSum")?? 0,
            'valueSumBanken' => $session->get("valueSumBanken")?? 0,
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5,]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4,'cards1' => 5,]),
        ];
        return $this->json($gameState);
    }
}
