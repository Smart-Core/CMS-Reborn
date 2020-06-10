<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait NameUniqueNotBlank
{
    /**
     * @ORM\Column(type="string", length=190, nullable=false, unique=true)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    protected string $name;

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getName(): string
    {
        return (string) $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = trim((string) $name);

        return $this;
    }
}
