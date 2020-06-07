<?php

declare(strict_types=1);

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\RadBundle\Doctrine\ColumnTrait;
use SmartCore\Bundle\MediaBundle\Provider\ProviderInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="media_storages")
 * @UniqueEntity(fields={"code"}, message="Code must be unique")
 */
class Storage
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Title;

    /**
     * Уникальный код хранилища
     *
     * @ORM\Column(type="string", length=2, nullable=false, unique=true)
     */
    protected string $code;

    /**
     * @ORM\Column(type="string", length=190)
     */
    protected string $relative_path;

    /**
     * @var string instanceof ProviderInterface
     *
     * @ORM\Column(type="string", length=190, nullable=false)
     */
    protected string $provider;

    /**
     * @ORM\Column(type="array")
     */
    protected array $arguments;

    public function __construct(string $relativePath = '')
    {
        $this->created_at       = new \DateTime();
        $this->relative_path    = $relativePath;
        $this->title            = 'Новое хранилище';
        $this->provider         = 'SmartCore\Bundle\MediaBundle\Provider\LocalProvider';
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->title;
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
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     *
     * @return $this
     */
    public function setProvider(string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     *
     * @return $this
     */
    public function setArguments(array $arguments): self
    {
        $this->arguments = $arguments;

        return $this;
    }
}
