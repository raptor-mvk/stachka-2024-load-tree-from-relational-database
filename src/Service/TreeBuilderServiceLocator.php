<?php

namespace App\Service;

use App\Model\NodeTypeEnum;

class TreeBuilderServiceLocator
{
    /** @var array<string,TreeBuilderInterface> */
    private array $treeBuilders = [];

    public function registerTreeBuilder(NodeTypeEnum $nodeType, TreeBuilderInterface $treeBuilder): void
    {
        $this->treeBuilders[$nodeType->value] = $treeBuilder;
    }

    public function getTreeBuilder(NodeTypeEnum $nodeType): TreeBuilderInterface
    {
        return $this->treeBuilders[$nodeType->value];
    }
}