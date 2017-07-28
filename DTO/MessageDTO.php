<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\SwiftReaderBundle\DTO;

class MessageDTO
{
    public $date;
    public $from = array();
    public $to = array();
    public $cc = array();
    public $bcc = array();
    public $subject;
    public $body;

    /**
     * @param \Swift_Mime_Message $message
     */
    public function fill(\Swift_Mime_Message $message)
    {
        $this->date = (new \DateTime(sprintf('@%d', $message->getDate())))->format('Y-m-d H:i:s');
        $message->getFrom() && $this->from = array_keys($message->getFrom());
        $message->getTo() && $this->to = array_keys($message->getTo());
        $message->getCc() && $this->cc = array_keys($message->getCc());
        $message->getBcc() && $this->bcc = array_keys($message->getBcc());
        $this->subject = $message->getSubject();
        $this->body = $message->getBody();
    }
}
