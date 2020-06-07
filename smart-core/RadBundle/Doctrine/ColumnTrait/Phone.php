<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;

trait Phone
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $phone;

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = trim($phone);

        return $this;
    }
}
