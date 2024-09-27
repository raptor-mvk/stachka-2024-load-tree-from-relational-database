<?php

namespace App\Service;

use App\Model\Node;

interface TreeBuilderInterface
{
    public function setParent(TreeBuilderInterface $treeBuilder);

    public function addChild(NodeResolverInterface $nodeResolver);

    public function fillChildren(array $parents, int $depth): void;

    public function getData(int $id): ?Node;

    /** @return Node[] */
    public function getPath(int $id): array;
}