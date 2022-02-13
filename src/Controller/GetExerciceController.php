<?php

namespace App\Controller;

use App\Repository\ExerciceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GetExerciceController extends AbstractController
{
    public function __invoke(ExerciceRepository $exerciceRepository ): Response
    {   
        $exercice = $exerciceRepository->findBy(['User' => $this->getUser()],['id' => 'DESC']);
        return $this->json($exercice);


    }

}