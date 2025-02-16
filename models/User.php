<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Model\Repository\UserRepository;

//#[ORM\Entity]
#[ORM\Table(name: 'users')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
  
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'user_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $username;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;

    #[ORM\Column(name: 'first_name', type: 'string', length: 255)]
    private string $firstName;

    #[ORM\Column(name: 'last_name', type: 'string', length: 255)]
    private string $lastName;

    #[ORM\Column(name: 'phone_number', type: 'string', length: 20, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $address = null;

    #[ORM\Column(name: 'confirmation_token', type: 'string', nullable: true)]
    private ?string $confirmationToken = null; // The confirmation token field


    #[ORM\Column(name: 'is_confirmed', type: 'boolean', options: ["default" => "0"])]
    private bool $isConfirmed = false; // Default to false

    #[ORM\ManyToOne(targetEntity: Role::class)]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'role_id', nullable: true)]
    private ?Role $role = null;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime')]
    private \DateTime $updatedAt;

    #[ORM\Column(name: 'reset_token', type: 'string', length: 150, nullable: true)]
private ?string $resetToken = null;

#[ORM\Column(name: 'reset_token_expires', type: 'datetime', nullable: true)]
private ?\DateTime $resetTokenExpires = null;

    public function __construct(
        string $username,
        string $password,
        string $email,
        string $firstName,
        string $lastName
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): void
    {
        $this->role = $role;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

      // Getter and Setter for confirmationToken
      public function getConfirmationToken(): ?string
      {
          return $this->confirmationToken;
      }
  
      public function setConfirmationToken(string $token): void
      {
          $this->confirmationToken = $token; // Set the confirmation token
      }

        // Getter and setter for isConfirmed
    public function getIsConfirmed(): bool
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed(bool $isConfirmed): void
    {
        $this->isConfirmed = $isConfirmed;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }
    
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }
    
    public function getResetTokenExpires(): ?\DateTime
    {
        return $this->resetTokenExpires;
    }
    
    public function setResetTokenExpires(?\DateTime $resetTokenExpires): void
    {
        $this->resetTokenExpires = $resetTokenExpires;
    }
    
    // Add a helper method to check if reset token is expired
    public function isResetTokenExpired(): bool
    {
        if (!$this->resetTokenExpires) {
            return true;
        }
        
        return $this->resetTokenExpires < new \DateTime();
    }    

}
