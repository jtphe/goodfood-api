<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
#[ApiResource]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'string', length: 15)]
    private $phone;

    #[ORM\Column(type: 'string', length: 75)]
    private $address;

    #[ORM\Column(type: 'string', length: 15)]
    private $postcalCode;

    #[ORM\Column(type: 'string', length: 55)]
    private $city;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $photo;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'restaurant')]
    private $users;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Comment::class, orphanRemoval: true)]
    private $comments;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'restaurant')]
    #[ORM\JoinColumn(nullable: false)]
    private $country;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Propose::class, orphanRemoval: true)]
    private $proposes;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Supplier::class)]
    private $suppliers;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Supply::class)]
    private $supplies;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Order::class)]
    private $orders;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->proposes = new ArrayCollection();
        $this->suppliers = new ArrayCollection();
        $this->supplies = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostcalCode(): ?string
    {
        return $this->postcalCode;
    }

    public function setPostcalCode(string $postcalCode): self
    {
        $this->postcalCode = $postcalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRestaurant($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRestaurant() === $this) {
                $comment->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Propose>
     */
    public function getProposes(): Collection
    {
        return $this->proposes;
    }

    public function addPropose(Propose $propose): self
    {
        if (!$this->proposes->contains($propose)) {
            $this->proposes[] = $propose;
            $propose->setRestaurant($this);
        }

        return $this;
    }

    public function removePropose(Propose $propose): self
    {
        if ($this->proposes->removeElement($propose)) {
            // set the owning side to null (unless already changed)
            if ($propose->getRestaurant() === $this) {
                $propose->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Supplier>
     */
    public function getSuppliers(): Collection
    {
        return $this->suppliers;
    }

    public function addSupplier(Supplier $supplier): self
    {
        if (!$this->suppliers->contains($supplier)) {
            $this->suppliers[] = $supplier;
            $supplier->setRestaurant($this);
        }

        return $this;
    }

    public function removeSupplier(Supplier $supplier): self
    {
        if ($this->suppliers->removeElement($supplier)) {
            // set the owning side to null (unless already changed)
            if ($supplier->getRestaurant() === $this) {
                $supplier->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Supply>
     */
    public function getSupplies(): Collection
    {
        return $this->supplies;
    }

    public function addSupply(Supply $supply): self
    {
        if (!$this->supplies->contains($supply)) {
            $this->supplies[] = $supply;
            $supply->setRestaurant($this);
        }

        return $this;
    }

    public function removeSupply(Supply $supply): self
    {
        if ($this->supplies->removeElement($supply)) {
            // set the owning side to null (unless already changed)
            if ($supply->getRestaurant() === $this) {
                $supply->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setRestaurant($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getRestaurant() === $this) {
                $order->setRestaurant(null);
            }
        }

        return $this;
    }
}