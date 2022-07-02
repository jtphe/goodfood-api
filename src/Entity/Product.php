<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource]
class Product implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    /**
     * @Groups("read")
     */
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    /**
     * @Groups("read")
     */
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    /**
     * @Groups("read")
     */
    private $description;

    #[ORM\Column(type: 'float')]
    /**
     * @Groups("read")
     */
    private $price;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    /**
     * @Groups("read")
     */
    private $image;

    #[ORM\ManyToOne(targetEntity: restaurant::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $restaurant;

    #[ORM\Column(type: 'integer')]
    /**
     * @Groups("read")
     */
    private $stock;

    #[ORM\Column(type: 'integer', nullable: true)]
    /**
     * @Groups("read")
     */
    private $discount;


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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @ReturnTypeWillChange
     * @return mixed
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @psalm-pure
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'name'=> $this->name,
            'description'=> $this->description,
            'image'=> $this->image,
            'price'=> $this->price,
            'discount'=> $this->discount,
            'stock'=> $this->stock,
            'restaurant' => $this->restaurant  ? $this->restaurant->getId() : null
        );
    }


    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

}
