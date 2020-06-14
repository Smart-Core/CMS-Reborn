<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Command;

use SmartCore\RadBundle\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;

class CreateAdminLteSymlinkCommand extends AbstractCommand
{
    const METHOD_COPY = 'copy';
    const METHOD_ABSOLUTE_SYMLINK = 'absolute symlink';
    const METHOD_RELATIVE_SYMLINK = 'relative symlink';

    protected static $defaultName = 'cms:adminlte:create-symlink';

    private $filesystem;

    protected function configure()
    {
        $this
            ->setDescription('Create symlink from vendor/almasaeed2010/adminlte to CMSBundle/Resources/public/assets/adminlte')
        ;
    }

    public function __construct(KernelInterface $kernel, Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->kernel     = $kernel;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectDir = $this->kernel->getProjectDir();

        $cmsPublicDir = $projectDir.'/smart-core/CMSBundle/Resources/public/assets/adminlte';

        if (is_dir($cmsPublicDir) or is_file($cmsPublicDir)) {
            $this->filesystem->remove($cmsPublicDir);
        }

        $originDir = $projectDir.'/vendor/almasaeed2010/adminlte';

        $method = $this->absoluteSymlinkWithFallback($originDir, $cmsPublicDir);

        $this->io->text('vendor/almasaeed2010/adminlte -> CMSBundle/Resources/public/assets/adminlte : '.$method);

        return 0;
    }

    /**
     * Try to create absolute symlink.
     *
     * Falling back to hard copy.
     *
     * @param string $originDir
     * @param string $targetDir
     *
     * @return string
     */
    private function absoluteSymlinkWithFallback(string $originDir, string $targetDir): string
    {
        try {
            $this->symlink($originDir, $targetDir);
            $method = self::METHOD_ABSOLUTE_SYMLINK;
        } catch (IOException $e) {
            // fall back to copy
            $method = $this->hardCopy($originDir, $targetDir);
        }

        return $method;
    }

    /**
     * Copies origin to target.
     *
     * @param string $originDir
     * @param string $targetDir
     *
     * @return string
     */
    private function hardCopy(string $originDir, string $targetDir): string
    {
        $this->filesystem->mkdir($targetDir, 0777);
        // We use a custom iterator to ignore VCS files
        $this->filesystem->mirror($originDir, $targetDir, Finder::create()->ignoreDotFiles(false)->in($originDir));

        return self::METHOD_COPY;
    }

    /**
     * Creates symbolic link.
     *
     * @param string $originDir
     * @param string $targetDir
     * @param bool   $relative
     *
     * @throws IOException if link can not be created
     */
    private function symlink(string $originDir, string $targetDir, $relative = false)
    {
        if ($relative) {
            $this->filesystem->mkdir(dirname($targetDir));
            $originDir = $this->filesystem->makePathRelative($originDir, realpath(dirname($targetDir)));
        }

        $this->filesystem->symlink($originDir, $targetDir);

        if (!file_exists($targetDir)) {
            throw new IOException(sprintf('Symbolic link "%s" was created but appears to be broken.', $targetDir), 0, null, $targetDir);
        }
    }
}
