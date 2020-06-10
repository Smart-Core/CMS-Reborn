<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Entity\Content;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\RadBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cms_content_dataset")
 *
 * @UniqueEntity(fields="name")
 * @UniqueEntity(fields="slug")
 */
class Dataset
{
    use ColumnTrait\Id;
    use ColumnTrait\NameUniqueNotBlank;
    use ColumnTrait\Description;
    use ColumnTrait\Slug32;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\UpdatedAt;
    use ColumnTrait\User;

    /**
     * @ORM\Column(type="string", length=100,  nullable=true)
     */
    protected ?string $icon;

    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->description  = null;
        $this->icon         = null;
        $this->name         = '';
        $this->slug         = '';
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param string|null $icon
     *
     * @return $this
     */
    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }
}
