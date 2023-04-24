<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\CardGraphic as CardG;
use App\Card\DeckWith2Jokers as CardJ;

class CardController extends AbstractController
{
    /**
     * @Route("/card", name="card")
     */
    public function card(): Response
    {
        $data = [
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5,]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4,'cards1' => 5,]),
        ];
        return $this->render('card/card.html.twig', $data);
    }

    /**
     * @Route("/card/deck", name="deck")
     */
    public function deck(): Response
    {
        $die = new CardG();

        $cards = $die->initEnglishDeck();


        $data = [
            'title' => 'Graphic dice rolled many times',
            'cards' => $cards,
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5,]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4,'cards1' => 5,]),


        ];
        return $this->render('card/deck.html.twig', $data);
    }

    /**
     * @Route("card/deck/shuffle", name="shuffle")
     */
    public function shuffle(SessionInterface $session): Response
    {
        $shu = new CardG();

        $cards = $shu->shuffleCards();
        $session->set("cards", $cards);

        $data = [
            'title' => 'Graphic dice rolled many times',
            'cards' => $cards,
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5,]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4,'cards1' => 5,]),


        ];
        return $this->render('card/shuffle.html.twig', $data);
    }

     /**
     * @Route(
     *       "card/deck/draw",
     *       name="draw",
     *       methods={"GET","HEAD"}
     * )
     */
    public function draw(): Response
    {
        $shu = new CardG();
        $cards = $shu->shuffleCards();

        $data = [
            'title' => 'Graphic dice rolled many times',
            'cards' => $cards,
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5,]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4,'cards1' => 5,]),

        ];
        return $this->render('card/draw.html.twig', $data);
    }

    /**
     * @Route(
     *      "card/deck/draw",
     *      name="card-draw",
     *      methods={"POST"}
     * )
     */
    public function drawpross(
        Request $request,
        SessionInterface $session
    ): Response {
        $shu = new CardG();
        $cards = $session->get("cards");
        $draw  = $request->request->get('draw');
        $start = $request->request->get('start');
        $sum = $session->get("sum") ?? 0;
        $cardn = $session->get("cardn");
        $color = $session->get("color");
        if ($draw) {
            if (!empty($cards)) {
                $cardn = array_key_first($cards);
                $color = reset($cards);
                unset($cards[$cardn]);
                $newcards = array_values($cards);
                $sum = count($newcards);
                $session->set("sum", $sum);
                $session->set("cards", $cards);
                $session->set("cardn", $cardn);
                $this->addFlash("info", "$cardn");


                $session->set("color", $color);
            }
            $this->addFlash("warning", " You have $sum cards left please click 'Start' if you want to start again.");
        } elseif ($start) {
            $this->addFlash("warning", "You will start the game.");
            $sum = 0;
            $cards = $shu->shuffleCards();
            $session->set("sum", 0);
            $session->set("cards", $cards);
        }
        $data = [
            'title' => 'Graphic dice rolled many times',
            'color' => $color,
            'cards' => $cards,
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5,]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4,'cards1' => 5,]),

        ];

        return $this->render('card/draw.html.twig', $data);
    }
     /**
     * @Route(
     *       "card/deck/draw/{numDraw}",
     *       name="drawnum",
     *       methods={"GET","HEAD"}
     * )
     */
    public function drawnum(): Response
    {
        $shu = new CardG();
        $cards = $shu->shuffleCards();

        $data = [
            'title' => 'Graphic dice rolled many times',
            'cards' => $cards,
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5,]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4,'cards1' => 5,]),

        ];
        return $this->render('card/drawnum.html.twig', $data);
    }
        /**
     * @Route(
     *      "card/deck/draw/{numDraw}",
     *      name="card-drawnum",
     *      methods={"POST"}
     * )
     */
    public function drawpross2(
        int $numDraw,
        Request $request,
        SessionInterface $session
    ): Response {
        $shu = new CardG();
        $cards = $session->get("cards");
        $draw  = $request->request->get('draw');
        $start = $request->request->get('start');
        $cardn = $session->get("cardn");
        $color = $session->get("color");
        $sum = $session->get("sum") ?? 0;
        $colorarr =[];
        if ($draw) {
            if (!empty($cards)) {
                for ($i = 1; $i <= $numDraw; $i++) {
                    $cardn = array_key_first($cards);
                    $color = reset($cards);
                    unset($cards[$cardn]);
                    $colorarr[$cardn]=$color;
                    $newcards = array_values($cards);
                    $sum = count($newcards);
                    $session->set("sum", $sum);
                    $session->set("cards", $cards);
                    $session->set("cardn", $cardn);
                }

                $session->set("color", $color);
            }
            $this->addFlash("warning", " You have $sum cards left please click 'Start' if you want to start again.");
        } elseif ($start) {
            $this->addFlash("warning", "You will start the game.");
            $sum = 0;
            $cards = $shu->shuffleCards();
            $session->set("sum", 0);
            $session->set("cards", $cards);
        }
        $data = [
            'title' => 'Graphic dice rolled many times',
            'colors' => $colorarr,
            'cards' => $cards,
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5,]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4,'cards1' => 5,]),

        ];

        return $this->render('card/drawnum.html.twig', $data);
    }
     /**
     * @Route(
     *       "card/deck/deal/{players}/{cards1}",
     *       name="deal",
     *       methods={"GET","HEAD"}
     * )
     */
    public function drawPl(

        ): Response {
        $shu = new CardG();
        $cards = $shu->shuffleCards();

        $data = [
            'title' => 'Graphic dice rolled many times',
            'cards' => $cards,
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5,]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4,'cards1' => 5,]),

        ];
        return $this->render('card/deal.html.twig', $data);
    }
        /**
     * @Route(
     *      "card/deck/deal/{players}/{cards1}",
     *      name="card-deal",
     *      methods={"POST"}
     * )
     */
    public function drawprossPl(
        int $cards1,
        int $players,
        Request $request,
        SessionInterface $session
    ): Response {
        $shu = new CardG();

        $cards = $session->get("cards");
        $draw  = $request->request->get('draw');
        $start = $request->request->get('start');
        $cardn = $session->get("cardn");
        $color = $session->get("color");
        $sum = $session->get("sum") ?? 0;
        $colorarr =[];
        if ($draw) {
            if (!empty($cards)) {
                for ($i = 1; $i <= $cards1*$players; $i++) {
                    $cardn = array_key_first($cards);
                    $color = reset($cards);
                    unset($cards[$cardn]);
                    $colorarr[$cardn]=$color;
                    $newcards = array_values($cards);
                    $sum = count($newcards);
                    $session->set("sum", $sum);
                    $session->set("cards", $cards);
                    $session->set("cardn", $cardn);
                }

                $session->set("color", $color);
            }
            $this->addFlash("warning", " You have $sum cards left please click 'Start' if you want to start again.");
        } elseif ($start) {
            $this->addFlash("warning", "You will start the game.");
            $sum = 0;
            $cards = $shu->shuffleCards();
            $session->set("sum", 0);
            $session->set("cards", $cards);
        }
        $data = [
            'title' => 'Graphic dice rolled many times',
            'colors' => $colorarr,
            'cards' => $cards,
            'cards1' => $cards1,

            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5,]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4,'cards1' => 5,]),

        ];

        return $this->render('card/deal.html.twig', $data);
    }
    /**
     * @Route("/card/deck2", name="deck2")
     */
    public function deck2(): Response
    {
        $die = new Cardj();

        $cards2 = $die->initEnglishDeck2Jokers();


        $data = [
            'title' => 'Graphic dice rolled many times',
            'cards2' => $cards2,
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5,]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4,'cards1' => 5,]),


        ];
        return $this->render('card/deck2.html.twig', $data);
    }
}
