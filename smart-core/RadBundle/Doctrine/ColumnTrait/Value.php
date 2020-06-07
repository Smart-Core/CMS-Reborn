<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;

trait Value
{
    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    protected ?string $value;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = trim($value);

        return $this;
    }
}
