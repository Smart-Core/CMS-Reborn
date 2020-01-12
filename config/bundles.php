<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],
    SmartCore\Bundle\DbDumperBundle\SmartDbDumperBundle::class => ['all' => true],
    SmartCore\Bundle\FelibBundle\FelibBundle::class => ['all' => true],
    SmartCore\Bundle\HtmlBundle\HtmlBundle::class => ['all' => true],
    SmartCore\Bundle\MediaBundle\SmartMediaBundle::class => ['all' => true],
    SmartCore\Bundle\SettingsBundle\SmartSettingsBundle::class => ['all' => true],
    SmartCore\CMSBundle\CMSBundle::class => ['all' => true],
    Liip\ImagineBundle\LiipImagineBundle::class => ['all' => true],
    Oneup\FlysystemBundle\OneupFlysystemBundle::class => ['all' => true],
    Cache\AdapterBundle\CacheAdapterBundle::class => ['all' => true],
];
