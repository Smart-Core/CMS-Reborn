<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\ColumnTrait;

trait IsEnabled
{
    /**
     * @ORM\Column(type="boolean", nullable=false,  options={"default":1})
     */
    protected bool $is_enabled;

    public function isIsEnabled(): bool
    {
        return $this->is_enabled;
    }

    public function setIsEnabled(bool $is_enabled): self
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    public function getIsEnabledAsText(): string
    {
        return $this->is_enabled ? 'Yes' : 'No';
    }

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    public function isDisabled(): bool
    {
        return !$this->is_enabled;
    }
}
