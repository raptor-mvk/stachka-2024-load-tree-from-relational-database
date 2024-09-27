<?php

namespace App\Service;

abstract class AbstractNodeResolver implements NodeResolverInterface
{
    protected readonly TreeBuilderInterface $treeBuilder;

    public function setTreeBuilder(TreeBuilderInterface $treeBuilder): void
    {
        $this->treeBuilder = $treeBuilder;
    }

    public function fillChildren(array $parents, int $depth): void
    {
    }
}