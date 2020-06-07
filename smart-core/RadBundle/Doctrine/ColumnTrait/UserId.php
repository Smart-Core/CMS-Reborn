<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;

trait UserId
{
    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    protected ?string $user_id;

    /**
     * @param int|string|object $user_id
     */
    public function setUserId($user_id): self
    {
        if (is_object($user_id)) {
            if (method_exists($user_id, 'getId')) {
                $user_id = $user_id->getId();
            } else {
                $user_id = (string) $user_id;
            }
        }

        if (empty($user_id)) {
            $user_id = null;
        }

        $this->user_id = $user_id;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->user_id;
    }
}
