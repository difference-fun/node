<?php

namespace Difference\Fun\Node\Trait;

use Exception;
use Difference\Fun\App;
use Difference\Fun\Exception\FileWriteException;
use Difference\Fun\Exception\ObjectException;
use Difference\Fun\Module\Cli;
use Difference\Fun\Module\Controller;
use Difference\Fun\Module\Core;
use Difference\Fun\Module\Data as Storage;
use Difference\Fun\Module\Dir;
use Difference\Fun\Module\File;
use Difference\Fun\Module\Sort;
use Difference\Fun\Node\Service\Security;

Trait Transaction {

    /**
     * @throws Exception
     */
    public function startTransaction($class, $options=[]): bool
    {
        $name = Controller::name($class);
        $object = $this->object();
        $has_transaction = $object->config('node.transaction.' . $name);
        if($has_transaction === true){
            throw new Exception('Transaction already started for class: ' . $name);
        }
        $this->lock($name, $options);
        return true;
    }

    /**
     * @throws ObjectException
     * @throws FileWriteException
     * @throws Exception
     */
    public function commit($class, $role, $options=[]): false | array
    {
        $start = microtime(true);
        $name = Controller::name($class);
        $object = $this->object();
        $options = Core::object($options, Core::OBJECT_ARRAY);
        $app_options = App::options($object);
        if (array_key_exists('time_limit', $options)) {
            set_time_limit((int)$options['time_limit']);
        }
        elseif ($object->config('time.limit')) {
            set_time_limit((int)$object->config('time.limit')); // 10 minutes
        } else {
            set_time_limit(600); // 10 minutes
        }
        $options['function'] = __FUNCTION__;
        $options['relation'] = false;
        if (!Security::is_granted(
            $name,
            $role,
            $options
        )) {
            $this->unlock($name);
            return false;
        }
        if (property_exists($app_options, 'force')) {
            $options['force'] = $app_options->force;
        }
        $is_transaction = $object->config('node.transaction.' . $name);
        if($is_transaction !== true){
            return false;
        }
        $result = [];
        //version 2 should append in json-line
        //make url sha1(url) of class
        $dir_data = $object->config('project.dir.node') .
            'Data' .
            $object->config('ds')
        ;
        $url = $dir_data .
            $name .
            $object->config('extension.json')
        ;
        $cache = $object->data(App::CACHE);
        $data = $cache->data(sha1($url));
        if($data){
            $start = microtime(true);
            $bytes = $data->write($url);
            $duration = microtime(true) - $start;
            $speed = $bytes / $duration;
            $result['bytes'] = $bytes;
            $result['size'] = File::size_format($bytes);
            $result['speed'] = File::size_format($speed) . '/sec';
            File::permission($object, [
                'dir_data' => $dir_data,
                'url' => $url,
            ]);
        } else {
            throw new Exception('Commit-data not found for url: ');
        }
        $this->unlock($name);
        return $result;
    }
}