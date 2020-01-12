Smart Core CMS Bundle
=====================

Установка
---------

1. Загрузка конфигов CMS.
```php
// /src/App/Kernel.php

// добавить в метод configureContainer()
$confDirCms = $this->getBundle('CMSBundle')->getPath().'/Resources/config';
$loader->load($confDirCms.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
$loader->load($confDirCms.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
```

2. Подключение маршрутов

фронтент и бакенд одним файлом
```yaml
# /config/routes.yaml

smart_core_cms:
    resource: '@CMSBundle/Resources/config/routing/routing.yml'
    prefix: /
```

Либо подключить по отдельности фронтент и бакенд:
```yaml
# /config/routes.yaml

smart_core_cms_admin:
    resource: '@CMSBundle/Resources/config/routing/routing_admin.yml'
    prefix: /%admin_path%/

smart_core_cms_frontend:
    resource: '@CMSBundle/Resources/config/routing/routing_frontend.yml'
    prefix: /
``` 

Если нужно изменить маршрут до админки, то можно задать в параметрах:
```yaml
# /config/services.yaml

parameters:
    admin_path: myadmin

```


3. Настройка переводов
```yaml
# /config/packages/translation.yaml
framework:
    default_locale: ru
    translator:
        default_path: '%kernel.project_dir%/translations'
        fallbacks:
            - en
```

4. Создать сущность пользователя. По умолчанию `App\Entity\User`
```php
<?php
# /src/Entity/User.php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use SmartCore\CMSBundle\Model\UserModel;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table("users",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"last_login"}),
 *          @ORM\Index(columns={"is_enabled"}),
 *      },
 * )
 *
 * @UniqueEntity(fields="username_canonical", errorPath="username", message="Username is already exists")*
 * @UniqueEntity(fields="email_canonical", errorPath="email", message="Email is already exists")
 */
class User extends UserModel
{
    use ColumnTrait\Id;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
}
```

Если класс пользователя, не `App\Entity\User`, то указать тут:
```yaml
# /config/packages/doctrine.yaml
doctrine:
    orm:
        resolve_target_entities:
            Symfony\Component\Security\Core\User\UserInterface: My\Entity\User
 
```

5. Конфигурирование security
```yaml
# /config/packages/security.yaml
security:
    always_authenticate_before_granting: true
    encoders:
        Symfony\Component\Security\Core\User\UserInterface: auto

    providers:
        database_users:
            entity: { class: App\Entity\User, property: username_canonical }

    firewalls:
        admin:
            anonymous: ~
            context: cms
            pattern: ^/%admin_path%
            form_login:
                check_path: /%admin_path%/
                login_path: /%admin_path%/
                default_target_path: /%admin_path%/
            logout:
                path: /%admin_path%/logout
    
    # access_control нельзя переопределить, по этому нужно описывать полностью в одном месте в /config/packages/security.yaml.
    access_control:
        - { path: ^/_assistant/, roles: ROLE_ADMIN }
        - { path: ^/%admin_path%/$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/%admin_path%/resetting$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/%admin_path%, roles: ROLE_ADMIN }
        - { path: ^/efconnect, role: ROLE_FILEMANAGER }
        - { path: ^/elfinder, role: ROLE_FILEMANAGER }

```

