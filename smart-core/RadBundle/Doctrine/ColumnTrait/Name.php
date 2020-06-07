<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;

trait Name
{
    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    protected ?string $name;

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = trim($name);

        return $this;
    }
}
