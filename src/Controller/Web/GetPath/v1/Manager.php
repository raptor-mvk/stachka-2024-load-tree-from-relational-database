<?php

namespace App\Controller\Web\GetPath\v1;

use App\Model\Node;
use App\Model\NodeTypeEnum;
use App\Service\TreeBuilderServiceLocator;

class Manager
{
    public function __construct(private readonly TreeBuilderServiceLocator $treeBuilderServiceLocator)
    {
    }

    /** @return Node[] */
    public function getPath(NodeTypeEnum $nodeType, int $id): array
    {
        $treeBuilder = $this->treeBuilderServiceLocator->getTreeBuilder($nodeType);

        return $treeBuilder->getPath($id);
    }
}