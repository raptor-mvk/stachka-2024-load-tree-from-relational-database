<?php

namespace App\Service;

use App\Model\Node;

interface NodeResolverInterface
{
    public function getData(int $id): ?Node;

    public function fillChildren(array $parents, int $depth): void;

    public function setTreeBuilder(TreeBuilderInterface $treeBuilder): void;
}