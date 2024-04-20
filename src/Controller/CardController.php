<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckWith2Jokers as CardJ;

class CardController extends AbstractController
{
    private function getCardData(SessionInterface $session): array
    {
        $shu = new CardJ();
        $cards = $shu->shuffleCards();

        return [
            'cards' => $cards,
            'link_to_drawnum' => $this->generateUrl('drawnum', ['numDraw' => 5]),
            'link_to_deal' => $this->generateUrl('deal', ['players' => 4, 'cards1' => 5]),
        ];
    }

    private function drawCards(SessionInterface $session, Request $request, int $cardsToDraw): array
    {
        $shu = new CardJ();
        $cards = $session->get("cards");
        $draw = $request->request->get('draw');
        $start = $request->request->get('start');
        $sum = $session->get("sum") ?? 0;
        $colorarr = [];

        if ($draw) {
            if (!empty($cards)) {
                for ($i = 1; $i <= $cardsToDraw; $i++) {
                    $cardn = array_key_first($cards);
                    $color = reset($cards);
                    unset($cards[$cardn]);
                    $colorarr[$cardn] = $color;
                    $newcards = array_values($cards);
                    $sum = count($newcards);
                    $session->set("sum", $sum);
                    $session->set("cards", $cards);
                    $session->set("cardn", $cardn);
                }
                $session->set("color", $color);
            }
            $this->addFlash("warning", "You have $sum cards left. Click 'Start' to start again.");
        } elseif ($start) {
            $this->addFlash("warning", "You will start the game.");
            $sum = 0;
            $cards = $shu->shuffleCards();
            $session->set("sum", 0);
            $session->set("cards", $cards);
        }

        return [
            'colors' => $colorarr,
            'cards' => $cards,
        ];
    }

    /**
     * @Route("/card", name="card")
     */
    public function card(SessionInterface $session): Response
    {
        return $this->render('card/card.html.twig', $this->getCardData($session));
    }


    /**
     * @Route("/card/deck", name="deck")
     */
    public function deck(): Response
    {
        $data = $this->getCardData($this->get('session'));
        $data['title'] = 'Graphic dice rolled many times';
        $data['cards'] = (new CardJ())->initEnglishDeck();

        return $this->render('card/deck.html.twig', $data);
    }

    /**
     * @Route("/card/deck/shuffle", name="shuffle")
     */
    public function shuffle(SessionInterface $session): Response
    {
        $data = $this->getCardData($session);
        $session->set("cards", $data['cards']);

        return $this->render('card/shuffle.html.twig', $data);
    }

    /**
     * @Route("/card/deck/draw", name="draw")
     */
    public function draw(): Response
    {
        $data = $this->getCardData($this->get('session'));

        return $this->render('card/draw.html.twig', $data);
    }

    /**
     * @Route("/card/deck/draw", name="card-draw", methods={"POST"})
     */
    public function drawpross(Request $request, SessionInterface $session): Response
    {
        $data = $this->drawCards($session, $request, 1);

        return $this->render('card/draw.html.twig', $data);
    }

    /**
     * @Route("/card/deck/draw/{numDraw}", name="drawnum")
     */
    public function drawnum(int $numDraw): Response
    {
        $data = $this->getCardData($this->get('session'));

        return $this->render('card/drawnum.html.twig', $data);
    }

    /**
     * @Route("/card/deck/draw/{numDraw}", name="card-drawnum", methods={"POST"})
     */
    public function drawpross2(int $numDraw, Request $request, SessionInterface $session): Response
    {
        $data = $this->drawCards($session, $request, $numDraw);

        return $this->render('card/drawnum.html.twig', $data);
    }

    /**
     * @Route("/card/deck/deal/{players}/{cards1}", name="deal")
     */
    public function drawPl(): Response
    {
        $data = $this->getCardData($this->get('session'));
        $data['title'] = 'Graphic dice rolled many times';

        return $this->render('card/deal.html.twig', $data);
    }

    /**
     * @Route("/card/deck/deal/{players}/{cards1}", name="card-deal", methods={"POST"})
     */
    public function drawprossPl(int $cards1, int $players, Request $request, SessionInterface $session): Response
    {
        $data = $this->drawCards($session, $request, $cards1 * $players);
        $data['cards1'] = $cards1;

        return $this->render('card/deal.html.twig', $data);
    }

    /**
     * @Route("/card/deck2", name="deck2")
     */
    public function deck2(): Response
    {
        $data = $this->getCardData($this->get('session'));
        $data['title'] = 'Graphic dice rolled many times';
        $data['cards2'] = (new CardJ())->initEnglishDeck2Jokers();

        return $this->render('card/deck2.html.twig', $data);
    }
}
