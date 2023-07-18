<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(length: 255)]
    private ?string $FirstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Phone = null;

    #[ORM\Column(length: 255)]
    private ?string $Email = null;

    #[ORM\Column(type: "string")]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    private Collection $orders;


    #[ORM\OneToMany(targetEntity: CustomerAddress::class, mappedBy: "user", cascade: ["persist"])]
    private Collection $addresses;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

public function __construct()
{
    $this->orders = new ArrayCollection();
    $this->addresses = new ArrayCollection();
}

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->Phone;
    }

    public function setPhone(?string $Phone): self
    {
        $this->Phone = $Phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
    // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    public function getUsername()
    {
        return $this->Email;
    }

    public function getRoles(): array
{
    $roles = $this->roles;
    $roles[] = 'ROLE_USER';
    $roles[] = 'ROLE_ADMIN';

    return array_unique($roles);
}

public function setRoles(array $roles): self
{
    $this->roles = $roles;

    return $this;
}

public function eraseCredentials(): void
{
}

public function getUserIdentifier(): string
{
    return $this->Email;
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
        $this->orders->add($order);
        $order->setUser($this);
    }

    return $this;
}

public function removeOrder(Order $order): self
{
    if ($this->orders->removeElement($order)) {
        if ($order->getUser() === $this) {
            $order->setUser(null);
        }
    }

    return $this;
}
    /**
     * @return Collection<int, CustomerAddress>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(CustomerAddress $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(CustomerAddress $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }
        return $this;
    }
}

