<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait TitleUniqueNotBlank
{
    /**
     * @ORM\Column(type="string", length=190, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    protected string $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = trim((string) $title);

        return $this;
    }
}
