<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\RadBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Данные тем оформления
 *
 * @ORM\Entity()
 * @ORM\Table(name="cms_themes_data")
 */
class ThemeData
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\UpdatedAt;
    use ColumnTrait\User;

    /**
     * Constructor.
     */
    public function __construct()
    {

    }
}
