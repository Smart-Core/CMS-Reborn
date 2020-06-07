<?php

declare(strict_types=1);

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\RadBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_categories",
 *      indexes={
 *          @ORM\Index(columns={"title"})
 *      }
 * )
 */
class Category
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Title;
    use ColumnTrait\Slug32;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children", fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $parent;

    /**
     * @var Category[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent", fetch="LAZY")
     * @ORM\OrderBy({"slug" = "ASC"})
     */
    protected $children;

    /**
     * @var File[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="File", mappedBy="category", fetch="EXTRA_LAZY")
     */
    protected $files;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->files      = new ArrayCollection();
    }
}
