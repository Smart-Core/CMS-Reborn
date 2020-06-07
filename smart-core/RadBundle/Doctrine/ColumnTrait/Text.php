<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

trait Text
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $text;

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = trim($text);

        return $this;
    }
}
