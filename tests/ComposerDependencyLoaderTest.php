<?php

namespace Outstack\TwigLoader\Tests;

use Outstack\TwigLoader\ComposerDependencyLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

class ComposerDependencyLoaderTest extends TestCase
{
    private ComposerDependencyLoader $loader;
    private string $vendorRoot;

    public function setUp(): void
    {
        $this->vendorRoot = __DIR__ . '/fixtures';
        $this->loader = new ComposerDependencyLoader($this->vendorRoot);
    }

    public function test_it_loads_example_template(): void
    {
        $this->assertTrue($this->loader->exists('~vendor1/package1/example.twig'));
        $template = $this->loader->getSourceContext('~vendor1/package1/example.twig');

        $this->assertSame($this->vendorRoot.'/vendor1/package1/templates/example.twig', $template->getPath());
        $this->assertSame('~vendor1/package1/example.twig', $template->getName());
        $this->assertSame('Example!', $template->getCode());
    }

    public function test_it_does_not_load_other_template_formats(): void
    {
        $this->assertFalse($this->loader->exists('vendor1/package1/example.twig'));
    }

    public function test_it_returns_cache_is_fresh_based_on_file_modification_time(): void
    {
        ClockMock::register(__CLASS__);
        ClockMock::register(ComposerDependencyLoader::class);
        ClockMock::withClockMock(true);

        touch($this->vendorRoot.'/vendor1/package1/templates/example.twig', time());
        ClockMock::withClockMock(false);
    }
}
