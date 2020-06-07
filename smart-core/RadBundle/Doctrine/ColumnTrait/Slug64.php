<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;

trait Slug64
{
    /**
     * @ORM\Column(type="string", length=64, nullable=false, unique=true)
     */
    protected string $slug;

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = trim($slug);

        return $this;
    }
}
