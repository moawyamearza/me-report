<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    /**
     * @Route("/report", name="report")
     */
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    /**
     * @Route("/api/lucky/number", name="lucky-number")
     */
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'message' => 'Welcome to the lucky number API',
            'lucky-number' => $number
        ];

        return new JsonResponse($data);
    }
    /**
     * @Route("/dev/debug", name="debug")
     */
    public function debug(): Response
    {
        $data = [
            'message' => 'Welcome to the lucky number API',
            'number' => random_int(0, 100),
        ];
        return $this->render('debug.html.twig', $data);
    }
}
