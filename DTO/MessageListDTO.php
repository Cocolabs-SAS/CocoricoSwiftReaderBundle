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

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class MessageListDTO
{
    public $messages = array();

    /**
     * MessageListDTO constructor.
     *
     * @param Finder $files
     */
    public function fill(Finder $files)
    {
        /** @var SplFileInfo $file */
        foreach ($files as $file) {

            preg_match("/(\d+)_([^']+)_([^']+).json/", $file->getFilename(), $matches);

            array_push(
                $this->messages,
                array(
                    'date' => (new \DateTime(sprintf('@%d', $matches[1])))->format('Y-m-d H:i:s'),
                    'subject' => base64_decode($matches[2]),
                    'filename' => $file->getFilename()
                )
            );
        }
    }
}
