<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\GetAvisController;
use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
#[ApiResource(

    collectionOperations: [
        'post'=> [
            'denormalization_context' => ['groups' => ['write:avis']],
        ],
        'get'=> [
            "method" => "GET",
            "path" => "/avis",
            "controller" => GetAvisController::class,
        ]   
    ],
    itemOperations: [
        'get' => ["security" => "is_granted('ROLE_ADMIN') or object.getUser() == user"],
        'delete' => ["security" => "is_granted('ROLE_ADMIN') or object.getUser == user"],
        'put' => ["security" => "is_granted('ROLE_ADMIN') or object.getUser == user"],
    ],
    normalizationContext: ['groups' => ['read:avis']],
)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:avis", "write:avis"])]
    private $content;

    #[ORM\Column(type: 'integer')]
    #[Groups(["read:avis", 'write:avis'])]
    private $difficultyLvl;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'avis')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read:avis"])]
    private $User;

    #[ORM\OneToOne(inversedBy: 'avis', targetEntity: Seance::class, cascade: ['persist', 'remove'])]
    #[Groups(["read:avis","write:avis"])]
    private $Seance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDifficultyLvl(): ?int
    {
        return $this->difficultyLvl;
    }

    public function setDifficultyLvl(int $difficultyLvl): self
    {
        $this->difficultyLvl = $difficultyLvl;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getSeance(): ?Seance
    {
        return $this->Seance;
    }

    public function setSeance(?Seance $Seance): self
    {
        $this->Seance = $Seance;

        return $this;
    }
}
