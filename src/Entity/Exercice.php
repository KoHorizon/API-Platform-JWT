<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateExerciceController;
use App\Controller\GetExerciceController;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: [
        'post'=> [
            "method" => "POST",
            "path" => "/exercices",
            "controller" => CreateExerciceController::class,
        ],
        'get'=> [
            "method" => "GET",
            "path" => "/exercices",
            "controller" => GetExerciceController::class,
        ],

    ],
    itemOperations: [
        'get' => ["security" => "is_granted('ROLE_ADMIN') or object.getUser() == user"],
        'delete'=> ["security" => "is_granted('ROLE_ADMIN') or object.getUser() == user"],
    ],

    denormalizationContext: ['groups' => ['exercice:write']],
    normalizationContext: ['groups' => ['exercice:read']],
)]


#[ORM\Entity(repositoryClass: ExerciceRepository::class)]
class Exercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Groups(["exercice:read", "exercice:write"])]
    private $name;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Groups(["exercice:read", "exercice:write"])]
    private $sets;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Groups(["exercice:read", "exercice:write"])]
    private $reps;

    #[ORM\ManyToOne(targetEntity: Seance::class, inversedBy: 'exercices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["exercice:read", "exercice:write"])]
    private $Seance;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'exercices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["exercice:read"])]
    private $User;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSets(): ?int
    {
        return $this->sets;
    }

    public function setSets(int $sets): self
    {
        $this->sets = $sets;

        return $this;
    }

    public function getReps(): ?int
    {
        return $this->reps;
    }

    public function setReps(int $reps): self
    {
        $this->reps = $reps;

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

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
