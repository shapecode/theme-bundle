<?php

namespace Shapecode\Bundle\ThemeBundle\EventListener;

use Shapecode\Bundle\ThemeBundle\Theme\ActiveTheme;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class ThemeRequestListener
 *
 * @package Shapecode\Bundle\ThemeBundle\EventListener
 *
 * @author  Tobias Ebnöther <ebi@liip.ch>
 * @author  Pascal Helfenstein <pascal@liip.ch>
 */
class ThemeRequestListener
{

    /** @var ActiveTheme */
    protected $activeTheme;

    /** @var array */
    protected $cookieOptions;

    /** @var string */
    protected $newTheme;

    /**
     * @param ActiveTheme $activeTheme
     * @param array       $cookieOptions
     */
    public function __construct(ActiveTheme $activeTheme, array $cookieOptions = null)
    {
        $this->activeTheme = $activeTheme;
        $this->cookieOptions = $cookieOptions;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (is_null($this->activeTheme)) {
            return;
        }

        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            $cookieValue = null;
            if (null !== $this->cookieOptions) {
                $cookieValue = $event->getRequest()->cookies->get($this->cookieOptions['name']);
            }

            if ($cookieValue && $cookieValue !== $this->activeTheme->getName()
                && in_array($cookieValue, $this->activeTheme->getThemes())
            ) {
                $this->activeTheme->setName($cookieValue);
                // store into cookie
                if ($this->cookieOptions) {
                    $this->newTheme = $cookieValue;
                }
            }
        }
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (is_null($this->activeTheme)) {
            return;
        }

        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            // store into the cookie only if the controller did not already change the value
            if ($this->newTheme == $this->activeTheme->getName()) {
                $cookie = new Cookie(
                    $this->cookieOptions['name'],
                    $this->newTheme,
                    time() + $this->cookieOptions['lifetime'],
                    $this->cookieOptions['path'],
                    $this->cookieOptions['domain'],
                    (bool)$this->cookieOptions['secure'],
                    (bool)$this->cookieOptions['http_only']
                );
                $event->getResponse()->headers->setCookie($cookie);
            }
        }
    }
}
