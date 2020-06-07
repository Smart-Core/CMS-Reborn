<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\RadBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cms_languages",
 *      indexes={
 *          @ORM\Index(columns={"is_enabled"}),
 *          @ORM\Index(columns={"position"})
 *      }
 * )
 *
 * @UniqueEntity(fields={"name"}, message="Язык с таким именем уже существует.")
 * @UniqueEntity(fields={"code"}, message="Язык с таким кодом уже существует.")
 */
class Language
{
    use ColumnTrait\Id;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\NameUnique;
    use ColumnTrait\Position;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\UpdatedAt;
    use ColumnTrait\User;

    /**
     * @ORM\Column(type="string", unique=true, length=12)
     */
    protected string $code;

    /**
     * @var Domain[]
     *
     * @ORM\OneToMany(targetEntity="Domain", mappedBy="language")
     */
    protected Collection $domains;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->code       = '';
        $this->domains    = new ArrayCollection();
        $this->is_enabled = true;
        $this->position   = 0;
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
     * @return Collection|Domain[]
     */
    public function getDomains(): Collection
    {
        return $this->domains;
    }

    /**
     * @param Collection|Domain[] $domains
     *
     * @return $this
     */
    public function setDomains($domains): self
    {
        $this->domains = $domains;

        return $this;
    }
}
