<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // Import Assert
use App\Entity\Category;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: "Le nom d'un article doit comporter au moins {{ limit }} caractères",
        maxMessage: "Le nom d'un article doit comporter au plus {{ limit }} caractères"
    )]
    private ?string $nom = null; // Fixing property name to be consistent with getter/setter

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)] // Fixed scale to 2 for decimal
    #[Assert\NotEqualTo(
        value: 0,
        message: "Le prix d’un article ne doit pas être égal à 0"
    )]
    private ?float $prix = null; // Change to float for price

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom; // Fixed property name
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom; // Fixed property name

        return $this;
    }

    public function getPrix(): ?float // Changed to float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static // Changed to accept float
    {
        $this->prix = $prix;

        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }








}
