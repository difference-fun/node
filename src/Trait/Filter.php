<?php
namespace Difference\Fun\Node\Trait;

use Difference\Fun\Module\Core;
use Difference\Fun\Module\Controller;
use Difference\Fun\Module\Data as Storage;
use Difference\Fun\Module\File;
use Difference\Fun\Module\Filter as Module;
use Difference\Fun\Module\Parse;

use Exception;

use Difference\Fun\Exception\FileWriteException;
use Difference\Fun\Exception\ObjectException;

trait Filter {

    /**
     * @throws Exception
     */
    private function filter($record=[], $filter=[], $options=[]): mixed
    {

        $list = [];
        $list[] = $record;
        $list = Module::list($list)->where($filter);
        if(!empty($list)){
            return $record;
        }
        return false;
    }
}