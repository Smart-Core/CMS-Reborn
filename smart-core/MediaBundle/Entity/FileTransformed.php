<?php

declare(strict_types=1);

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\MediaBundle\Repository\FileTransformedRepository;
use SmartCore\RadBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity(repositoryClass="FileTransformedRepository::class")
 * @ORM\Table(name="media_files_transformed",
 *      indexes={
 *          @ORM\Index(columns={"collection"}),
 *          @ORM\Index(columns={"storage"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"filter", "file_id"}),
 *      }
 * )
 */
class FileTransformed
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;

    /**
     * @var File
     *
     * @ORM\ManyToOne(targetEntity="File", inversedBy="filesTransformed", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $file;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2)
     */
    protected $collection;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2)
     */
    protected $storage;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     */
    protected $filter;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $size;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    /**
     * @return string
     */
    public function getFullRelativeUrl()
    {
        return $this->getFile()->getFullRelativeUrl($this->getFilter());
    }

    /**
     * @param File $file
     *
     * @return $this
     */
    public function setFile(File $file)
    {
        $this->file = $file;
        $this->setCollection($file->getCollection());
        $this->setStorage($file->getStorage());

        return $this;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $filter
     *
     * @return $this
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getCollection(): string
    {
        return $this->collection;
    }

    /**
     * @param string $collection
     *
     * @return $this
     */
    public function setCollection(string $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @return string
     */
    public function getStorage(): string
    {
        return $this->storage;
    }

    /**
     * @param string $storage
     *
     * @return $this
     */
    public function setStorage(string $storage): self
    {
        $this->storage = $storage;

        return $this;
    }
}
