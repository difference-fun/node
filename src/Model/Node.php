<?php
namespace Difference\Fun\Node\Model;

use Difference\Fun\App;

use Difference\Fun\Module\Data as Storage;
use Difference\Fun\Module\Template\Main;

use Difference\Fun\Node\Trait\Data;
use Difference\Fun\Node\Trait\Role;

class Node extends Main {
    use Data;
    use Role;

    public function __construct(App $object){
        $this->object($object);
        $this->storage(new Storage());
    }
}