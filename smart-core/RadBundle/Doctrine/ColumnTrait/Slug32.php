<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait Slug32
{
    /**
     * @ORM\Column(type="string", length=32, nullable=false, unique=true)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    protected string $slug;

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = trim((string) $slug);

        return $this;
    }
}
