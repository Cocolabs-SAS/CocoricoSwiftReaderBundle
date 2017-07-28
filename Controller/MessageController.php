<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\SwiftReaderBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/")
 */
class MessageController extends Controller
{
    /**
     * @Route("/", name="cocorico_swift_reader_message_index")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $this->get('profiler')->disable();

        $messageListDTO = $this->get('cocorico.swift_reader.message_manager')
            ->getMessageListDTO();

        return $this->render(
            'CocoricoSwiftReaderBundle:Message:index.html.twig',
            array(
                'messages' => $messageListDTO->messages
            )
        );
    }

    /**
     * @Route("/show/{filename}", name="cocorico_swift_reader_message_show")
     * @Method("GET")
     *
     * @param string $filename
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($filename)
    {
        $message = $this->get('cocorico.swift_reader.message_manager')
            ->getOneByFileName($filename);

        return $this->render(
            'CocoricoSwiftReaderBundle:Message:_show.html.twig',
            array(
                'filename' => $filename,
                'message' => $message
            )
        );
    }

    /**
     * @Route("/fetch/{filename}", name="cocorico_swift_reader_message_fetch")
     * @Method("GET")
     *
     * @param string $filename
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchAction($filename)
    {
        $this->get('profiler')->disable();

        $message = $this->get('cocorico.swift_reader.message_manager')
            ->getOneByFileName($filename);

        return new Response($message->body);
    }

    /**
     * @Route("/delete/{filename}", name="cocorico_swift_reader_message_delete")
     * @Method("GET")
     *
     * @param string $filename
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($filename)
    {
        $this->get('cocorico.swift_reader.message_manager')
            ->deleteByFileName($filename);

        $messageListDTO = $this->get('cocorico.swift_reader.message_manager')
            ->getMessageListDTO();

        return $this->render(
            'CocoricoSwiftReaderBundle:Message:_list.html.twig',
            array(
                'messages' => $messageListDTO->messages
            )
        );
    }

    /**
     * @Route("/refresh", name="cocorico_swift_reader_message_refresh")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function refreshAction()
    {
        $messageListDTO = $this->get('cocorico.swift_reader.message_manager')
            ->getMessageListDTO();

        return $this->render(
            'CocoricoSwiftReaderBundle:Message:_list.html.twig',
            array(
                'messages' => $messageListDTO->messages
            )
        );
    }

    /**
     * @Route("/clear", name="cocorico_swift_reader_message_clear")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function clearAction()
    {
        $this->get('cocorico.swift_reader.message_manager')->clear();

        return $this->render(
            'CocoricoSwiftReaderBundle:Message:_list.html.twig',
            array(
                'messages' => array()
            )
        );
    }
}
