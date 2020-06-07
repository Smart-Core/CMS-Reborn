<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\RadBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cms_sites")
 *
 * @UniqueEntity(fields={"name"}, message="Сайт с таким именем уже существует.")
 */
class Site
{
    use ColumnTrait\Id;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\NameUnique;
    use ColumnTrait\Position;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\UpdatedAt;
    use ColumnTrait\User;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $theme;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $web_root;

    /**
     * @ORM\OneToOne(targetEntity="Domain")
     * @ORM\JoinColumn(nullable=true)
     */
    protected ?Domain $domain;

    /**
     * @ORM\OneToOne(targetEntity="Folder")
     * @ORM\JoinColumn(nullable=true)
     */
    protected ?Folder $root_folder;

    /**
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(nullable=false)
     */
    protected Language $language;

    public function __construct(?string $name = null)
    {
        if ( ! empty($name)) {
            $this->name = $name;
        }

        $this->created_at = new \DateTime();
        $this->is_enabled = true;
        $this->position   = 0;
    }

    /**
     * @return string|null
     */
    public function getTheme(): ?string
    {
        return $this->theme;
    }

    /**
     * @param string|null $theme
     *
     * @return $this
     */
    public function setTheme(?string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWebRoot(): ?string
    {
        return $this->web_root;
    }

    /**
     * @param string|null $web_root
     *
     * @return $this
     */
    public function setWebRoot(?string $web_root): self
    {
        $this->web_root = $web_root;

        return $this;
    }

    /**
     * @return Domain|null
     */
    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    /**
     * @param Domain|null $domain
     *
     * @return $this
     */
    public function setDomain(?Domain $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return Folder|null
     */
    public function getRootFolder(): ?Folder
    {
        return $this->root_folder;
    }

    /**
     * @param Folder|null $root_folder
     *
     * @return $this
     */
    public function setRootFolder(?Folder $root_folder): self
    {
        $this->root_folder = $root_folder;

        return $this;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @param Language $language
     *
     * @return $this
     */
    public function setLanguage(Language $language): self
    {
        $this->language = $language;

        return $this;
    }
}
