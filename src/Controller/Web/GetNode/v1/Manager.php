<?php

namespace App\Controller\Web\GetNode\v1;

use App\Model\Node;
use App\Model\NodeTypeEnum;
use App\Service\TreeBuilderServiceLocator;

class Manager
{
    public function __construct(private readonly TreeBuilderServiceLocator $treeBuilderServiceLocator)
    {
    }

    public function getSubtree(NodeTypeEnum $nodeType, int $id, int $depth): Node
    {
        $treeBuilder = $this->treeBuilderServiceLocator->getTreeBuilder($nodeType);
        $node = $treeBuilder->getData($id);
        $treeBuilder->fillChildren([$node], $depth);

        return $node;
    }
}