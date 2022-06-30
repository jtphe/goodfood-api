<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource]
class Comment implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $rating;

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: restaurant::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    /**
     * @Ignore
     */
    private $restaurant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRestaurant(): ?restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'description'=> $this->description,
            'rating'=> $this->rating,
            'user'=> $this->user,
            'restaurant'=> $this->restaurant->getId(),
        );
    }
}
