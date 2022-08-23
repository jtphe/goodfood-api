<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    /**
     * @Groups("read")
     */
    private $id;

    #[ORM\Column(type: 'datetime')]
    /**
     * @Groups("read")
     */
    private $date;

    #[ORM\Column(type: 'boolean')]
    /**
     * @Groups("read")
     */
    private $archive;

    #[ORM\Column(type: 'float')]
    /**
     * @Groups("read")
     */
    private $price;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    /**
     * @Groups("read")
     */
    private $address;

    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    /**
     * @Groups("read")
     */
    private $postalCode;

    #[ORM\Column(type: 'string', length: 55, nullable: true)]
    /**
     * @Groups("read")
     */
    private $city;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    /**
     * @Groups("read")
     */
    private $payment;

    #[ORM\Column(type: 'integer')]
    /**
     * @Groups("read")
     */
    private $type;

    #[ORM\Column(type: 'integer')]
    /**
     * @Groups("read")
     */
    private $statut;

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: restaurant::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $restaurant;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'orders')]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'orderMenu', targetEntity: Menu::class)]
    private Collection $menus;


    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->menus = new ArrayCollection();
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

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPayment(): ?string
    {
        return $this->payment;
    }

    public function setPayment(?string $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(int $statut): self
    {
        $this->statut = $statut;

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
            'address'=> $this->address,
            'city'=> $this->city,
            'postalCode'=> $this->postalCode,
            'archive'=> $this->archive,
            'statut'=> $this->statut,
            'type'=> $this->type,
            'user'=> $this->user,
            'products'=>$this->products,
            'menus'=>$this->getMenus(),
            'restaurant'=> $this->restaurant->getId()
        );
    }

    public function isArchive(): ?bool
    {
        return $this->archive;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
             $this->menus[] = $menu;
             $menu->setOrderMenu($this);
        }

         return $this;
     }

    // public function removeMenu(Menu $menu): self
    // {
    //     if ($this->menus->removeElement($menu)) {
    //         // set the owning side to null (unless already changed)
    //         if ($menu->getOrderMenu() === $this) {
    //             $menu->setOrderMenu(null);
    //         }
    //     }

    //     return $this;
    // }


}
