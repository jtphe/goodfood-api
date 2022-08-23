<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ORM\Table(name: '`menu`')]
class Menu implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    /**
     * @Groups("read")
     */
    private ?int $id = null;

    #[ORM\Column]
    /**
     * @Groups("read")
     */
    private ?float $price = null;

    #[ORM\ManyToMany(targetEntity: product::class, inversedBy: 'menus')]
    /**
     * @Groups("read")
     */
    private Collection $products;

    #[ORM\ManyToOne(inversedBy: 'menus')]
    #[ORM\JoinColumn(nullable: false)]
    /**
     * @Groups("read")
     */
    private ?Order $orderMenu = null;



    public function __construct()
    {
        $this->products = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrderMenu(): ?Order
    {
        return $this->orderMenu;
    }

    public function setOrderMenu(?Order $orderMenu): self
    {
        $this->orderMenu = $orderMenu;

        return $this;
    }



    /**
     * @return Collection<int, product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(product $product): self
    {
        $this->products->removeElement($product);

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
            'price'=> $this->price,
            'food'=> $this->products[0],
            'snack'=> $this->products[1],
            'drink'=> $this->products[2],
            'order'=> $this->orderMenu->getId(),
        );

    }

}
