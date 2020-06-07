<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;

trait Address
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $address;

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = trim($address);

        return $this;
    }
}
