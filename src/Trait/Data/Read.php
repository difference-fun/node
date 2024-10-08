<?php

namespace Difference\Fun\Node\Trait\Data;

use Difference\Fun\Module\Controller;
use Difference\Fun\Module\Core;

use Difference\Fun\Node\Service\Security;

use Exception;

use Difference\Fun\Exception\FileWriteException;
use Difference\Fun\Exception\ObjectException;
trait Read {

    /**
     * @throws ObjectException
     * @throws FileWriteException
     * @throws Exception
     */
    public function read($class, $role, $options=[]): false | array | object
    {
        $object = $this->object();
        $name = Controller::name($class);
        $options = Core::object($options, Core::OBJECT_ARRAY);
        if(!array_key_exists('uuid', $options)){
            return false;
        }
        if(!array_key_exists('function', $options)){
            $options['function'] = __FUNCTION__;
        }
        if(!array_key_exists('relation', $options)){
            $options['relation'] = false;
        }
        if(!array_key_exists('parse', $options)){
            $options['parse'] = false;
        }
        if(!array_key_exists('memory', $options)){
            $options['memory'] = false;
        }
        if(!array_key_exists('duration', $options)){
            $options['duration'] = false;
        }
        $options_record = [
            'filter' => [
                "uuid" => [
                    'operator' => '===',
                    'value' => $options['uuid'],
                ]
            ],
            'function' => $options['function'],
            'relation' => $options['relation'],
            'parse' => $options['parse'],
            'memory' => $options['memory'],
            'duration' => $options['duration']
        ];

        if(!Security::is_granted(
            $class,
            $role,
            $options
        )){
            return false;
        }
        $ramdisk_record = $object->config('package.difference_fun/node.ramdisk');
        if(empty($ramdisk_record)){
            $ramdisk_record = [];
        }
        if(in_array(
            $name,
            $ramdisk_record,
            true
        )){
            $options_record['ramdisk'] = true;
        }
        if(array_key_exists('ramdisk', $options)){
            $options_record['ramdisk'] = $options['ramdisk'];
        }
        $data = $this->record($name, $role, $options_record);
        if($data){
            return $data;
        }
        return false;
    }
}