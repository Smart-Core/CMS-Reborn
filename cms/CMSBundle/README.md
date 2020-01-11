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

```yaml
# /config/routes.yaml

smart_core_cms:
    resource: '@CMSBundle/Resources/config/routing/routing.yml'
    prefix: /
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
