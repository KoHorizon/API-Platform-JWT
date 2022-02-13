<?php 


namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Avis;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AvisDataPersister implements ContextAwareDataPersisterInterface
{

    private EntityManagerInterface $entityManager;
    private $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager,TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }



    public function supports($data, array $context = []): bool
    {
        return $data instanceof Avis;
    }

    public function persist($data, array $context = [])
    {
        
        $user = $this->getUser();
        $data->setUser($user);
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }





    public function getUser(): ?User
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }


}