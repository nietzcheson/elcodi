<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @author Aldo Chiecchia <zimage@tiscali.it>
 * @author Elcodi Team <tech@elcodi.com>
 */

namespace Elcodi\Plugin\PaymillBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Elcodi\Component\Plugin\Entity\Plugin;

/**
 * Class AdminController
 */
class AdminController extends Controller
{
    /**
     * @Route(
     *      path = "/paymill",
     *      name = "admin_paymill_configuration",
     *      methods = {"GET", "POST"}
     * )
     * @Template()
     */
    public function configurationAction(Request $request)
    {
        /**
         * @var Plugin $plugin
         */
        $plugin = $this
            ->get('elcodi.plugin_manager')
            ->getPlugin('Elcodi\Plugin\PaymillBundle');

        if ('POST' === $request->getMethod()) {

            $paymillPrivateKey = $request->request->get('paymill_private_key');
            $paymillPublicKey = $request->request->get('paymill_public_key');

            $this
                ->get('elcodi.plugin_manager')
                ->updatePlugin(
                    'Elcodi\Plugin\PaymillBundle',
                    $plugin->isEnabled(),
                    [
                        'paymill_private_key' => $paymillPrivateKey,
                        'paymill_public_key' => $paymillPublicKey,
                    ]
                );

            /*
             * We also need to copy payment configuration keys
             * to elcodi_configuration
             */
            $configuration = $this->get('elcodi.manager.configuration');
            $configuration->set('store.paymill_private_key', $paymillPrivateKey);
            $configuration->set('store.paymill_public_key', $paymillPublicKey);

            return $this->redirectToRoute('admin_paymill_configuration');
        }

        return [
            'plugin' => $plugin,
        ];
    }
}
