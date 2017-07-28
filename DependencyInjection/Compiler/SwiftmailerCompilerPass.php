<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\SwiftReaderBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SwiftmailerCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter('swiftmailer.class')) {
            $container->setParameter('swiftmailer.class', 'Cocorico\SwiftReaderBundle\Extension\SwiftmailerExtension');
        }
        if ($container->hasDefinition('swiftmailer.mailer.default')) {
            $definition = $container->getDefinition('swiftmailer.mailer.default');
            $definition->addMethodCall('setEventDispatcher', array(new Reference('event_dispatcher')));
        }
    }
}
