<?php

namespace Event\Difference\Fun\Node;

use Event\Difference\Fun\Framework\Email;

use Difference\Fun\App;
use Difference\Fun\Config;

use Exception;

use Difference\Fun\Exception\ObjectException;

class Create {

    /**
     * @throws ObjectException
     * @throws Exception
     */
    public static function error(App $object, $event, $options=[]): void
    {
        if($object->config(Config::POSIX_ID) !== 0){
            return;
        }
        Create::notification($object, $event, $options);
    }

    /**
     * @throws Exception
     */
    public static function notification(App $object, $event, $options=[]): void
    {
        $action = $event->get('action');
        Email::queue(
            $object,
            $action,
            $options
        );
    }
}