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

Trait View {

    /**
     * @throws Exception
     */
    public function view_create($class, $role, $options=[])
    {
        $name = Controller::name($class);
        $object = $this->object();

        /**
         * need
         */

        $source = $object->config('project.dir.node') .
            'Data' .
            $object->config('ds') .
            $name .
            $object->config('extension.json')
        ;
        $data = $object->data_read($source);
        $list = [];
        $options_name = '';
        $options_name_finished = false;
        if($data){
            $count = 0;
            foreach($data->data($name) as $record){
                $count++;
                $node = new Storage($record);
                $new = new Storage();
                if(
                    property_exists($options, 'attribute') &&
                    is_array($options->attribute)
                ){
                    sort($options->attribute, SORT_NATURAL);
                    foreach($options->attribute as $nr => $attribute){
                        if(
                            $options_name_finished === false &&
                            $options_name === ''
                        ){
                            $options_name = $attribute;
                        }
                        elseif($options_name_finished === false) {
                            $options_name .= '-' . $attribute;
                        }
                        $new->data($attribute, $node->data($attribute));
                    }
                    $options_name_finished = true;
                }
                $new->data('uuid', $node->data('uuid'));
                $new->data('#class', $node->data('#class'));
                $dir_node = $object->config('ramdisk.url') .
                    $object->config('posix.id') .
                    $object->config('ds') .
                    'Node' .
                    $object->config('ds')
                ;
                $dir_view = $dir_node .
                    'View' .
                    $object->config('ds')
                ;

                $dir_view_class = $dir_view .
                    $name .
                    $object->config('ds')
                ;
                $dir_record = $dir_view_class .
                    'Record' .
                    $object->config('ds')
                ;
                if(!Dir::is($dir_record)) {
                    Dir::create($dir_record, Dir::CHMOD);
                }
                $create = $dir_record .
                    $node->data('uuid') .
                    $object->config('extension.json')
                ;
                if(!File::exist($create)){
                    File::write($create, Core::object($node->data(), Core::OBJECT_JSON));
                }
                $list[] = $new->data();
            }
        }
        $storage = new Storage();
        if(
            property_exists($options, 'ramdisk') &&
            $options->ramdisk === true
        ){
            $target = $object->config('ramdisk.url') .
                $object->config('posix.id') .
                $object->config('ds') .
                'Node' .
                $object->config('ds') .
                'View' .
                $object->config('ds') .
                $name .
                $object->config('ds') .
                'List' .
                $object->config('ds') .
                $options_name .
                $object->config('extension.json');
            $storage->data($name, $list);
            $storage->write($target);
            return $target;
        }
        return null;
    }
}