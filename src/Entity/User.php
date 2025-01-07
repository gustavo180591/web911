<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(length: 255)]
    private ?string $gender = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $dni = null;

    #[ORM\Column(length: 255)]
    private ?string $dniFrontPhoto = null;

    #[ORM\Column(length: 255)]
    private ?string $dniBackPhoto = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    private ?string $streetNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $locationDetails = null;

    #[ORM\Column]
    private ?bool $locationValidated = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $setCreatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $setIsActive = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    public function getDniFrontPhoto(): ?string
    {
        return $this->dniFrontPhoto;
    }

    public function setDniFrontPhoto(string $dniFrontPhoto): static
    {
        $this->dniFrontPhoto = $dniFrontPhoto;

        return $this;
    }

    public function getDniBackPhoto(): ?string
    {
        return $this->dniBackPhoto;
    }

    public function setDniBackPhoto(string $dniBackPhoto): static
    {
        $this->dniBackPhoto = $dniBackPhoto;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(string $streetNumber): static
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getLocationDetails(): ?string
    {
        return $this->locationDetails;
    }

    public function setLocationDetails(string $locationDetails): static
    {
        $this->locationDetails = $locationDetails;

        return $this;
    }

    public function isLocationValidated(): ?bool
    {
        return $this->locationValidated;
    }

    public function setLocationValidated(bool $locationValidated): static
    {
        $this->locationValidated = $locationValidated;

        return $this;
    }

    public function getSetCreatedAt(): ?\DateTimeImmutable
    {
        return $this->setCreatedAt;
    }

    public function setSetCreatedAt(\DateTimeImmutable $setCreatedAt): static
    {
        $this->setCreatedAt = $setCreatedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isSetIsActive(): ?bool
    {
        return $this->setIsActive;
    }

    public function setSetIsActive(bool $setIsActive): static
    {
        $this->setIsActive = $setIsActive;

        return $this;
    }
}
