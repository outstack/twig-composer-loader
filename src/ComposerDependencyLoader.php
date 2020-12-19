<?php

declare(strict_types=1);

namespace Outstack\TwigLoader;

use Twig\Error\LoaderError;
use Twig\Loader\LoaderInterface;
use Twig\Source;

class ComposerDependencyLoader implements LoaderInterface
{
    public function __construct(public string $vendorRoot) {}

    public function getSourceContext(string $name): Source
    {
        $path = $this->getAbsolutePath($name);
        return new Source(file_get_contents($path), $name, $path);
    }

    public function getCacheKey(string $name): string
    {
        return $this->getRelativePath($name);
    }

    public function isFresh(string $name, int $time): bool
    {
        return filemtime($this->getAbsolutePath($name)) < $time;
    }

    public function exists(string $name): bool
    {
        try {
            return file_exists($this->getAbsolutePath($name));
        } catch (LoaderError) {
            return false;
        }
    }

    private function getRelativePath(string $name): string
    {
        if (!str_starts_with($name, '~')) {
            throw new LoaderError(sprintf("Template %s does not start with a tilde (~)", $name));
        }
        $path = ltrim($name, '~');
        $parts = explode('/', $path);
        $parts = array_merge(
            array_slice($parts, 0, 2),
            ['templates'],
            array_slice($parts, 2)
        );
        $path = implode(DIRECTORY_SEPARATOR, $parts);

        return $path;
    }

    private function getAbsolutePath(string $name): string
    {
        return $this->vendorRoot . DIRECTORY_SEPARATOR . $this->getRelativePath($name);
    }

}
