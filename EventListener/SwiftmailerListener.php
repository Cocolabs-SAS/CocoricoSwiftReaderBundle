<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\SwiftReaderBundle\EventListener;

use Cocorico\SwiftReaderBundle\Event\SwiftmailerEvents;
use Cocorico\SwiftReaderBundle\Event\SwiftmailerSendEvent;
use Cocorico\SwiftReaderBundle\Manager\MessageManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SwiftmailerListener implements EventSubscriberInterface
{
    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * @param MessageManager $messageManager
     */
    public function __construct(MessageManager $messageManager)
    {
        $this->messageManager = $messageManager;
    }

    /**
     * @param SwiftmailerSendEvent $event
     * @throws \Symfony\Component\Serializer\Exception\NotEncodableValueException
     */
    public function onSend(SwiftmailerSendEvent $event)
    {
        $this->messageManager->write($event->getMessage());
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            SwiftmailerEvents::SEND => array('onSend'),
        );
    }

}