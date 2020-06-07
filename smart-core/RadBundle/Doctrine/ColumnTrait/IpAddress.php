<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait IpAddress
{
    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Ip()
     */
    protected ?string $ip_address;

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(?string $ip_address): self
    {
        $this->ip_address = trim($ip_address);

        return $this;
    }
}
