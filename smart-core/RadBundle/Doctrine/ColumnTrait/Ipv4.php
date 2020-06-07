<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait Ipv4
{
    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Ip(version="4")
     */
    protected ?string $ipv4;

    public function getIpv4(): ?string
    {
        return $this->ipv4;
    }

    public function setIpv4(?string $ipv4): self
    {
        $this->ipv4 = trim($ipv4);

        return $this;
    }
}
