<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Entity\Seance;
use App\Repository\ExerciceRepository;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateExerciceController extends AbstractController
{
    public function __invoke(ExerciceRepository $exerciceRepository,SeanceRepository $seanceRepository, Request $request, EntityManagerInterface $entityManager): Response
    {   
        $user = $this->getUser();
        $date = new \DateTimeImmutable('@'.strtotime('now'));
        $seance = New Seance;
        $seance->setDate($date);
        $seance->setUser($user);


        $valid_keys = array("name", "sets", "reps");
        $validFormat = false;
        $body = json_decode($request->getContent(), true);


        foreach ($body as $content) {
            foreach ($content as $key => $value) {
                if (in_array($key, $valid_keys)) {
                    $validFormat = true;
                } else {
                    return $this->json([
                        "response" => "Wrong format have been sent",
                        "needed keys" => ["name","sets","reps"]
                    ]);
                }
            }
        }
        $entityManager->persist($seance);
        $entityManager->flush();



        $lastSeance = $seanceRepository->findOneBy(['User' => $this->getUser()],['id' => 'DESC']);
        foreach ($body as $content) {
            $exercice = New Exercice;
            foreach ($content as $key => $value) {
                switch ($key) {
                    case "name":
                        $exercice->setName($value);
                        break;
                    case "sets":
                        $exercice->setSets($value);
                        break;
                    case "reps":
                        $exercice->setReps($value);
                        break;
                }
                $exercice->setSeance($lastSeance);
                $exercice->setUser($user);

            }
            $entityManager->persist($exercice);
            $entityManager->flush();
        }
        $createdExercice = $exerciceRepository->findBy(['Seance' => $lastSeance->getId()]);


        return $this->json(
            [   
                "response" => "Data have been created",
                "data" => $createdExercice
            ]
        );
    }

}