<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2015 Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author
 */
 

namespace Elcodi\Plugin\PaymillBundle\Templating;
use Elcodi\Component\Plugin\Templating\Traits\TemplatingTrait;
use Elcodi\Component\Plugin\Entity\Plugin;
use Elcodi\Component\Plugin\Interfaces\EventInterface;


/**
 * Class TwigRenderer
 */
class TwigRenderer
{
    use TemplatingTrait;

    /**
     * @var Plugin
     *
     * Plugin
     */
    protected $plugin;

    /**
     * Set plugin
     *
     * @param Plugin $plugin Plugin
     *
     * @return $this Self object
     */
    public function setPlugin(Plugin $plugin)
    {
        $this->plugin = $plugin;

        return $this;
    }

    /**
     * Renders google analytics JS
     *
     * @param EventInterface $event Event
     */
    public function renderJavascript(EventInterface $event)
    {
        return;

        if (
            !$this->plugin->isEnabled() ||
            !isset($this->plugin->getConfiguration()['analytics_tracker_id']) ||
            '' === $this->plugin->getConfiguration()['analytics_tracker_id']
        ) {
            return;
        }

        $this->appendTemplate(
            '@ElcodiPaymill/javascript.html.twig',
            $event,
            ['variable' => $this->plugin->getConfiguration()['vari_from_plugin_config']]
        );
    }
}
