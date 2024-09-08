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

trait Stats {

    public function stats($class, $response): void
    {
        if(
            $response &&
            array_key_exists('create', $response) &&
            array_key_exists('put', $response) &&
            array_key_exists('patch', $response) &&
            array_key_exists('commit', $response) &&
            array_key_exists('speed', $response['commit']) &&
            array_key_exists('item_per_second', $response)
        ) {
            $total = $response['create'] + $response['put'] + $response['patch'];
            if ($total === 1) {
                echo 'Imported' .
                    ' (create: ' .
                    $response['create'] .
                    ', put: ' .
                    $response['put'] .
                    ', patch: ' .
                    $response['patch'] .
                    ') ' .
                    $total .
                    ' item (' .
                    $class .
                    ') at ' .
                    $response['item_per_second'] .
                    ' items/sec (' .
                    $response['commit']['speed'] . ')' .
                    PHP_EOL;
            } else {
                echo 'Imported' .
                    ' (create: ' .
                    $response['create'] .
                    ', put: ' .
                    $response['put'] .
                    ', patch: ' .
                    $response['patch'] .
                    ') ' .
                    $total .
                    ' items (' .
                    $class .
                    ') at ' .
                    $response['item_per_second'] .
                    ' items/sec (' .
                    $response['commit']['speed'] . ')' .
                    PHP_EOL;
            }
        }
    }
}