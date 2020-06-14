Запуск в Docker
---------------

Документация тут [docker.md](doc/docker.md) 

Создание юзера
--------------

Посмотреть список всех пользователей:
```
bin/console user:list
```

Создание юзера:
```    
bin/console user:add
```

Назначить роль пользователю, например: ROLE_SUPER_ADMIN
```
bin/console user:role:promote <username> <role>
```

Для запуска команд в докере, нужно перед командой написать: `docker-compose run php` итого формат будет такой: 

```
docker-compose run --rm php <command>
# например:
docker-compose run --rm php bin/console user:list
```

Подключение CMS в проект
------------------------

Загрузка конфигов:

```php
// App\Kernel.php

// ...
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        $container->setParameter('container.dumper.inline_class_loader', \PHP_VERSION_ID < 70400 || $this->debug);
        $container->setParameter('container.dumper.inline_factories', true);
        $confDir = $this->getProjectDir().'/config';

        // Загрузка конфигов CMS.
        $confDirCms = $this->getBundle('CMSBundle')->getPath().'/Resources/config';
        $loader->load($confDirCms.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDirCms.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');

        // Потом конфиги приложения.
        $loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }
 // ...
```

Файрвол:

```yaml
# config/packages/security.yaml 
security:
    # ...
    firewalls:
        # ...        
        cms_admin:
            anonymous: ~
            context: cms
            pattern: ^/%cms.admin_path%
            form_login:
                check_path: /%cms.admin_path%/
                login_path: /%cms.admin_path%/
                default_target_path: /%cms.admin_path%/
            logout:
                path: /%cms.admin_path%/logout
            remember_me:
                secret: "%env(APP_SECRET)%"
                name: REMEMBER_ME
                lifetime: 31536000 # 365 days in seconds
                path: /
                domain: ~
    # ...

    access_control:
        # ...
        - { path: ^/%cms.admin_path%/$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/%cms.admin_path%, roles: ROLE_ADMIN }
        # ...
```

Маршруты

```yaml
# config/routes.yaml

# ...
smart_core_cms:
    resource: '@CMSBundle/Resources/config/routes.yaml'

```

Наборы данных
-------------

Поля таблиц можно задавать несколькими способами:

 1. Указать готовый трейт, в этом случае настроить можно только is_index.
 2. Указать доктрин Doctrine\DBAL\Types\Types, в этом случае можно как угодно настроить поле (пока что конфиг записывается тут: `Resourse/config/dataset.yaml`)
 
На каждое поле можно указать аннотации, например указав следующий набор будет создан первичный ключ:

```
@ORM\Id
@ORM\GeneratedValue(strategy="AUTO") // {"AUTO", "SEQUENCE", "TABLE", "IDENTITY", "NONE", "UUID", "CUSTOM"}
```

Валидаторы

TODO
----

Cornerstone -> Elements
