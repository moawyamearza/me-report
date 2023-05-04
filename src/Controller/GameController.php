<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\GameService;
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
        GameService $gameService
    ): Response {
        $newround = $request->request->get('newround');
        $draw  = $request->request->get('draw');
        $stop = $request->request->get('stop');
        if ($newround) {
            $session->clear();
        }
        $shu = new CardG();
        $shuBanken = new CardG();
        $cards = $session->get("cards") ?? $shu->shuffleCards();
        $cardsBanken = $session->get("cardsBanken") ?? $shuBanken->shuffleCards();

        $new = $session->get("new") ?? array();
        $newBanken = $session->get("newBanken") ?? array();
        $arrcardvalue = $session->get("arrcardvalue") ?? array();
        $arrcardvalueBanken = $session->get("arrcardvalueBanken") ?? array();
        $valueSum = $session->get("valueSum")?? 0;
        $valueSumBanken = $session->get("valueSumBanken")?? 0;
        if ($draw) {
            $result = $gameService->draw($cards, $new, $arrcardvalue, $valueSum);
            // Update variables
            $cards = $result['cards'];
            $new = $result['new'];
            $valueSum = $result['valueSum'];
            $arrcardvalue = $result['arrcardvalue'];

            // Update session variables
            $session->set("cards", $cards);
            $session->set("new", $new);
            $session->set("arrcardvalue", $arrcardvalue);
            $session->set("valueSum", $valueSum);
        } elseif ($stop) {
            for ($i = 1; $i <= 100; $i++) {
                
                    $result = $gameService->draw($cardsBanken, $newBanken, $arrcardvalueBanken, $valueSumBanken);
                    // access the returned variables
                    $cardsBanken = $result['cards'];
                    $newBanken = $result['new'];
                    $valueSumBanken = $result['valueSum'];
                    $arrcardvalueBanken = $result['arrcardvalue'];
                    // Update session variables
                    $session->set("cardsBanken", $cardsBanken);
                    $session->set("newBanken", $newBanken);
                    $session->set("arrcardvalueBanken", $arrcardvalueBanken);
                    $session->set("valueSumBanken", $valueSumBanken);
                
            }
        }
        $data = [
            'new' => $new,
            'valuesum' => $valueSum,
            'newBanken' => $newBanken,
            'valuesumBanken' => $valueSumBanken,
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
