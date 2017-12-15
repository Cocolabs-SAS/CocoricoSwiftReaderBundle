<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\SwiftReaderBundle\Extension;

use Cocorico\SwiftReaderBundle\Event\SwiftmailerEvents;
use Cocorico\SwiftReaderBundle\Event\SwiftmailerSendEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SwiftmailerExtension extends \Swift_Mailer
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param \Swift_Mime_SimpleMessage $message
     * @param array|null                $failedRecipients
     * @return int
     */
    public function send(\Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {

        $return = parent::send($message, $failedRecipients);

        $this->eventDispatcher
            ->dispatch(SwiftmailerEvents::SEND, new SwiftmailerSendEvent($message));

        return $return;
    }
}
