<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GetAvisController extends AbstractController
{
    public function __invoke(AvisRepository $avisRepository ): Response
    {   
        $avis = $avisRepository->findBy(['User' => $this->getUser()],['id' => 'DESC']);
        return $this->json($avis);
    }

}