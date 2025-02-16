<?php

namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'roles')]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'role_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'role_name', type: 'string', length: 255)]
    private string $roleName;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    /**
     * One Role can be assigned to many Users
     */
    #[ORM\OneToMany(mappedBy: 'role', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private Collection $users;

    public function __construct(string $roleName, ?string $description = null)
    {
        $this->roleName = $roleName;
        $this->description = $description;
        $this->users = new ArrayCollection();
    }

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoleName(): string
    {
        return $this->roleName;
    }

    public function setRoleName(string $roleName): void
    {
        $this->roleName = $roleName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setRole($this);
        }
    }

    public function removeUser(User $user): void
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->setRole(null);
        }
    }
}
