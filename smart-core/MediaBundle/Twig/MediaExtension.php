<?php

declare(strict_types=1);

namespace SmartCore\Bundle\MediaBundle\Twig;

use SmartCore\Bundle\MediaBundle\Service\MediaCloudService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MediaExtension extends AbstractExtension
{
    protected MediaCloudService $media;

    public function __construct($media)
    {
        $this->media = $media;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('smart_media',     [$this, 'generateFileUrl'], ['is_safe' => ['html']]),
            new TwigFunction('smart_media_img', [$this, 'renderImgTag'], ['is_safe' => ['html']]),
        ];
    }

    public function generateFileUrl(int $id, ?string $filter = null): ?string
    {
        return (empty($id)) ? null : $this->media->getFileUrl($id, $filter);
    }

    public function renderImgTag(int $id, ?string $filter = null, string $alt = ''): ?string
    {
        return (empty($id)) ? null : '<img src="'.$this->generateFileUrl($id, $filter).'" alt="'.$alt.'">';
    }

    public function getName(): string
    {
        return 'smart_media_twig_extension';
    }
}
