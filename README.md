Установка
---------

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
