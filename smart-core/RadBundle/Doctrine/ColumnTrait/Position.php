<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Position column
 */
trait Position
{
    /**
     * @ORM\Column(type="smallint", nullable=false, options={"default":0})
     * @Assert\Range(min = "0", max = "255")
     */
    protected int $position;

    /**
     * @param mixed $position
     */
    public function setPosition($position): self
    {
        if (empty($position)) {
            $position = 0;
        }

        $this->position = (int) $position;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}
