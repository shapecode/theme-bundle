<?php

namespace Shapecode\Bundle\ThemeBundle\Theme;

/**
 * Class ActiveTheme
 *
 * Contains the currently active theme and allows to change it.
 *
 * This is a service so we can inject it as reference to different parts of the application.
 *
 * @package Shapecode\Bundle\ThemeBundle\Theme
 * @author  Benjamin Eberlei <kontakt@beberlei.de>
 */
class ActiveTheme
{
    /** @var string */
    private $name;

    /** @var array */
    private $themes;

    /**
     * @param string $name
     * @param array  $themes
     */
    public function __construct($name, array $themes = array())
    {
        $this->setThemes($themes);

        if ($name) {
            $this->setName($name);
        }
    }

    /**
     * @return array
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * @param array $themes
     */
    public function setThemes(array $themes)
    {
        $this->themes = $themes;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        if (!in_array($name, $this->themes)) {
            throw new \InvalidArgumentException(sprintf(
                'The active theme "%s" must be in the themes list (%s)',
                $name, implode(',', $this->themes)
            ));
        }

        $this->name = $name;
    }
}
