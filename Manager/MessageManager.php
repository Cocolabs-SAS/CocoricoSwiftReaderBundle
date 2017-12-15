<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\SwiftReaderBundle\Manager;

use Cocorico\SwiftReaderBundle\DTO\MessageDTO;
use Cocorico\SwiftReaderBundle\DTO\MessageListDTO;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MessageManager
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var string
     */
    private $path;

    /**
     * MessageManager constructor.
     *
     * @param Filesystem $filesystem
     * @param string     $path
     */
    public function __construct(Filesystem $filesystem, $path)
    {
        $this->filesystem = $filesystem;
        $this->path = $path;
        $this->serializer = new Serializer(array(new ObjectNormalizer()), array(new JsonEncoder()));

        try {
            $this->filesystem->mkdir($this->path);
        } catch (IOExceptionInterface $e) {
            return;
        }
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @param \Swift_Mime_SimpleMessage $message
     * @throws \Symfony\Component\Serializer\Exception\NotEncodableValueException
     */
    public function write(\Swift_Mime_SimpleMessage $message)
    {
        $filename = sprintf(
            '%s/%s_%s_%s.json',
            $this->path,
            $message->getDate(),
            base64_encode($message->getSubject()),
            uniqid()
        );

        $messageDTO = new MessageDTO();
        $messageDTO->fill($message);
        $content = $this->serializer->serialize($messageDTO, 'json');

        try {
            $this->filesystem->dumpFile($filename, $content);

        } catch (IOExceptionInterface $e) {
            return;
        }
    }

    /**
     * @param $filename
     * @return null|object
     * @throws \RuntimeException
     * @throws \Symfony\Component\Serializer\Exception\NotEncodableValueException
     */
    public function getOneByFileName($filename)
    {
        $finder = new Finder();
        $files = $finder->files()->in($this->path)->name($filename);
        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            return $this->serializer->deserialize(
                $file->getContents(),
                'Cocorico\SwiftReaderBundle\DTO\MessageDTO',
                'json'
            );
        }

        return null;
    }

    /** @inheritdoc */
    public function deleteByFileName($filename)
    {
        $finder = new Finder();
        $files = $finder->files()->in($this->path)->name($filename);
        $this->filesystem->remove($files);
    }

    /** @inheritdoc */
    public function clear()
    {
        $finder = new Finder();
        $files = $finder->files()->in($this->path);
        $this->filesystem->remove($files);
    }

    /**
     * @return MessageListDTO
     */
    public function getMessageListDTO()
    {
        $finder = new Finder();
        $files = $finder->files()->in($this->path);
        $files->sort(
            function (SplFileInfo $a, SplFileInfo $b) {
                return strcmp($b->getFilename(), $a->getFilename());
            }
        );
        $messageListDTO = new MessageListDTO();
        $messageListDTO->fill($files);

        return $messageListDTO;
    }
}
