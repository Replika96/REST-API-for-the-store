<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LuckyController extends AbstractController
{
    #[Route('/lucky/number', name: 'app_lucky_number')]
    public function number(): Response
    {
        $number = random_int(0, 100);
        $list = ['Replika', 'Vadim' , 34, 52 , 17, 9];

        return $this->render('lucky/number.html.twig', [
            'number' => $number,
            'list' => $list
        ]);
    }
    
    #[Route('/lucky/test1')]
    public function test1(): Response
    {
        return $this->render('lucky/test1.html.twig');
    }
}