<?php

namespace Shapecode\Bundle\ThemeBundle\Twig\Loader;

use Shapecode\Bundle\ThemeBundle\Theme\ActiveTheme;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Twig\Loader\LoaderInterface;
use Twig\Source;

/**
 * Class FilesystemLoader
 *
 * @package Shapecode\Bundle\ThemeBundle\Twig\Loader
 * @author  Nikita Loges
 */
class FilesystemLoader implements LoaderInterface
{

    /** @var FileLocatorInterface */
    protected $locator;

    /** @var TemplateNameParserInterface */
    protected $parser;

    /** @var ActiveTheme|null */
    protected $activeTheme;

    /** @var array */
    protected $cache = [];

    /**
     * @param FileLocatorInterface        $locator
     * @param TemplateNameParserInterface $parser
     * @param ActiveTheme                 $activeTheme
     */
    public function __construct(FileLocatorInterface $locator, TemplateNameParserInterface $parser, ActiveTheme $activeTheme)
    {
        $this->locator = $locator;
        $this->parser = $parser;
        $this->activeTheme = $activeTheme;
    }

    /**
     * @inheritdoc
     */
    public function getSource($name)
    {
        return file_get_contents($this->findTemplate($name));
    }

    /**
     * @inheritdoc
     */
    public function getSourceContext($name)
    {
        $path = $this->findTemplate($name);

        return new Source(file_get_contents($path), $name, $path);
    }

    /**
     * @inheritdoc
     */
    public function getCacheKey($name)
    {
        return $name . '|' . $this->activeTheme->getName();
    }

    /**
     * @inheritdoc
     */
    public function exists($name)
    {
        if (is_null($this->activeTheme)) {
            return false;
        }

        $cacheKey = $this->getCacheKey($name);

        if (isset($this->cache[$cacheKey])) {
            return true;
        }

        return false !== $this->findTemplate($name, false);
    }

    /**
     * @inheritdoc
     */
    public function isFresh($name, $time)
    {
        return filemtime($this->findTemplate($name)) <= $time;
    }

    /**
     * @inheritdoc
     */
    protected function findTemplate($template, $throw = true)
    {
        if (is_null($this->activeTheme)) {
            return false;
        }

        $logicalName = (string)$template;
        $cacheKey = $this->getCacheKey($logicalName);

        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $file = null;
        $previous = null;

        try {
            $templateReference = $this->parser->parse($template);
            $file = $this->locator->locate($templateReference);
        } catch (\Exception $e) {
        }

        if (false === $file || null === $file) {
            if ($throw) {
                throw new \Twig_Error_Loader(sprintf('Unable to find template "%s".', $logicalName), -1, null, $previous);
            }

            return false;
        }

        return $this->cache[$cacheKey] = $file;
    }
}
