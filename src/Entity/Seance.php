<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\GetSeanceController;
use App\Repository\SeanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get'=> [
            "method" => "GET",
            "path" => "/seances",
            "controller" => GetSeanceController::class,
        ],
        'post'
    ],
    itemOperations: [
        'get',
        'delete',
        'put',
    ],

    denormalizationContext: ['groups' => ['seance:write']],
    normalizationContext: ['groups' => ['seance:read']],
)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["seance:read"])]
    private $date;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'seances')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["seance:read"])]
    private $User;

    #[ORM\OneToMany(mappedBy: 'Seance', targetEntity: Exercice::class, orphanRemoval: true)]
    #[Groups(["seance:read", "seance:write"])]
    private $exercices;

    #[ORM\OneToOne(mappedBy: 'Seance', targetEntity: Avis::class, cascade: ['persist', 'remove'])]
    #[Groups(["seance:read", "seance:write"])]
    private $avis;

    public function __construct()
    {
        $this->exercices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    /**
     * @return Collection|Exercice[]
     */
    public function getExercices(): Collection
    {
        return $this->exercices;
    }

    public function addExercice(Exercice $exercice): self
    {
        if (!$this->exercices->contains($exercice)) {
            $this->exercices[] = $exercice;
            $exercice->setSeance($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): self
    {
        if ($this->exercices->removeElement($exercice)) {
            // set the owning side to null (unless already changed)
            if ($exercice->getSeance() === $this) {
                $exercice->setSeance(null);
            }
        }

        return $this;
    }

    public function getAvis(): ?Avis
    {
        return $this->avis;
    }

    public function setAvis(?Avis $avis): self
    {
        // unset the owning side of the relation if necessary
        if ($avis === null && $this->avis !== null) {
            $this->avis->setSeance(null);
        }

        // set the owning side of the relation if necessary
        if ($avis !== null && $avis->getSeance() !== $this) {
            $avis->setSeance($this);
        }

        $this->avis = $avis;

        return $this;
    }
}
