<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\RadBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cms_themes")
 *
 * @UniqueEntity(fields="name")
 */
class Theme
{
    use ColumnTrait\Id;
    use ColumnTrait\NameUniqueNotBlank;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\User;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }
}
