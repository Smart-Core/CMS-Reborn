<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait EmailUnique
{
    /**
     * @ORM\Column(type="string", length=120, nullable=true, unique=true)
     * @Assert\Email(mode="strict")
     */
    protected ?string $email;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = trim($email);

        return $this;
    }
}
