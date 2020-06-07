<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

trait Comment
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $comment;

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = trim($comment);

        return $this;
    }
}
