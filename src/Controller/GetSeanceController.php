<?php

namespace App\Controller;

use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GetSeanceController extends AbstractController
{
    public function __invoke(SeanceRepository $seanceRepository ): Response
    {   
        $seances = $seanceRepository->findBy(['User' => $this->getUser()],['date' => 'DESC']);
        return $this->json($seances);
    }

}