<?php

namespace App\EventSubscriber;

use function json_last_error;
use function json_last_error_msg;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class BeforeActionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'convertJsonStringToArray',
        );
    }

    public function convertJsonStringToArray(ControllerEvent $event)
    {
        $request = $event->getRequest();
        // dd($request);
        if ($request->getContentType() != 'json' || !$request->getContent()) {
            return;
        }

        // function remove_utf8_bom($text)
        // {
        //     $bom = pack('H*','EFBBBF');
        //     $text = preg_replace("/^$bom/", '', $text);
        //     return $text;
        // }
        // $data1 = remove_utf8_bom($request->getContent());
        // $data = json_decode($data1, true);
        // dd($request->request->all());
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            
            throw new BadRequestHttpException('invalid json body: ' . json_last_error_msg());
        }

        $request->request->replace(is_array($data) ? $data : array());
    }

}