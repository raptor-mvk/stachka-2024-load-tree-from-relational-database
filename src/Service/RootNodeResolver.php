<?php

namespace App\Service;

use App\Model\Node;
use App\Model\NodeTypeEnum;

class RootNodeResolver extends AbstractNodeResolver
{
    public function getData(int $id): ?Node
    {
        return new Node(-1, 'Английский язык', NodeTypeEnum::Root->value, null);
    }
}
