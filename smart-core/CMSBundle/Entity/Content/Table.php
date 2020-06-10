<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Entity\Content;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\RadBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cms_content_tables",
 *      indexes={
 *          @ORM\Index(columns={"position"})
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"class_name", "dataset_id"}),
 *      }
 * )
 * @UniqueEntity(fields="name")
 * @UniqueEntity(fields="title")
 * @UniqueEntity(fields={"class_name", "dataset"})
 */
class Table
{
    use ColumnTrait\Id;
    use ColumnTrait\TitleUniqueNotBlank;
    use ColumnTrait\NameUniqueNotBlank;
    use ColumnTrait\Description;
    use ColumnTrait\Position;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\UpdatedAt;
    use ColumnTrait\User;

    /**
     * @ORM\Column(type="string", length=190, nullable=false)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    protected string $class_name;

    /**
     * @ORM\ManyToOne(targetEntity="Dataset", inversedBy="tables")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotBlank()
     */
    protected Dataset $dataset;

    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->description  = null;
        $this->icon         = null;
        $this->class_name   = '';
        $this->name         = '';
        $this->title        = '';
        $this->position     = 0;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getDataset(): Dataset
    {
        return $this->dataset;
    }

    public function setDataset(Dataset $dataset): self
    {
        $this->dataset = $dataset;

        return $this;
    }

    public function getClassName(): string
    {
        return $this->class_name;
    }

    public function setClassName(?string $class_name): self
    {
        $this->class_name = trim((string) $class_name);

        return $this;
    }
}
