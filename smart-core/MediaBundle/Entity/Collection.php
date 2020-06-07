<?php

declare(strict_types=1);

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\RadBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_collections")
 * @UniqueEntity(fields={"code"}, message="Code must be unique")
 */
class Collection
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\TitleNotBlank;

    /**
     * Уникальный код коллекции
     *
     * @ORM\Column(type="string", length=2, nullable=false, unique=true)
     */
    protected string $code;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $default_filter;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $upload_filter;

    /**
     * @ORM\Column(type="array")
     *
     * @deprecated
     */
    protected ?array $params;

    /**
     * @ORM\ManyToOne(targetEntity="Storage", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected Storage $storage;

    /**
     * Относительный путь можно менять, только если нету файлов в коллекции
     * либо предусмотреть как-то переименовывание пути в провайдере.
     *
     * @ORM\Column(type="string", length=190, nullable=false)
     */
    protected string $relative_path;

    /**
     * @ORM\Column(type="string", length=190, nullable=false)
     */
    protected string $file_relative_path_pattern;

    /**
     * Маска имени файла. Если пустая строка, то использовать оригинальное имя файла,
     * совместимое с вебформатом т.е. без пробелов и русских букв.
     *
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    protected string $filename_pattern;

    /**
     * @param string $relativePath
     */
    public function __construct($relativePath = '')
    {
        $this->created_at       = new \DateTime();
        $this->relative_path    = $relativePath;

        $this->filename_pattern            = '{hour}_{minutes}_{rand(10)}';
        $this->file_relative_path_pattern  = '/{year}/{month}/{day}';
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->title;
    }

    /**
     * @return Storage
     */
    public function getStorage(): Storage
    {
        return $this->storage;
    }

    /**
     * @param Storage $storage
     *
     * @return $this
     */
    public function setStorage(Storage $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDefaultFilter(): ?string
    {
        return $this->default_filter;
    }

    /**
     * @param string|null $default_filter
     *
     * @return $this
     */
    public function setDefaultFilter(?string $default_filter): self
    {
        $this->default_filter = $default_filter;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUploadFilter(): ?string
    {
        return $this->upload_filter;
    }

    /**
     * @param string|null $upload_filter
     *
     * @return $this
     */
    public function setUploadFilter(?string $upload_filter): self
    {
        $this->upload_filter = $upload_filter;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * @param array|null $params
     *
     * @return $this
     */
    public function setParams(?array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return string
     */
    public function getRelativePath(): string
    {
        return $this->relative_path;
    }

    /**
     * @param string $relative_path
     *
     * @return $this
     */
    public function setRelativePath(string $relative_path): self
    {
        $this->relative_path = $relative_path;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileRelativePathPattern(): string
    {
        return $this->file_relative_path_pattern;
    }

    /**
     * @param string $file_relative_path_pattern
     *
     * @return $this
     */
    public function setFileRelativePathPattern(string $file_relative_path_pattern): self
    {
        $this->file_relative_path_pattern = $file_relative_path_pattern;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilenamePattern(): string
    {
        return $this->filename_pattern;
    }

    /**
     * @param string $filename_pattern
     *
     * @return $this
     */
    public function setFilenamePattern(string $filename_pattern): self
    {
        $this->filename_pattern = $filename_pattern;

        return $this;
    }
}
