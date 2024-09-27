<?php

namespace App\Service;

use App\Model\Node;

class TreeBuilder implements TreeBuilderInterface
{
    private ?TreeBuilderInterface $parent = null;

    /** @var NodeResolverInterface[] */
    private array $childrenResolvers = [];

    public function __construct(private readonly NodeResolverInterface $nodeResolver)
    {
        $this->nodeResolver->setTreeBuilder($this);
    }


    public function setParent(TreeBuilderInterface $treeBuilder)
    {
        $this->parent = $treeBuilder;
    }

    public function addChild(NodeResolverInterface $nodeResolver)
    {
        $this->childrenResolvers[] = $nodeResolver;
    }

    public function fillChildren(array $parents, int $depth): void
    {
        foreach ($this->childrenResolvers as $nodeResolver) {
            $nodeResolver->fillChildren($parents, $depth);
        }
    }

    public function getData(int $id): ?Node
    {
        return $this->nodeResolver->getData($id);
    }

    public function getPath(int $id): array
    {
        $node = $this->getData($id);
        if ($node === null) {
            return [];
        }
        if ($this->parent === null) {
            return [$node];
        }

        return array_merge($this->parent->getPath($node->getParentId()), [$node]);
    }
}