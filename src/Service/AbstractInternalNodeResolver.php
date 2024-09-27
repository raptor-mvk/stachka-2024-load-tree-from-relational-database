<?php

namespace App\Service;

use App\Model\Node;

abstract class AbstractInternalNodeResolver extends AbstractNodeResolver implements NodeResolverInterface
{
    /**
     * @param int[] $parentIds
     * @return Node[]
     */
    abstract protected function getChildren(array $parentIds): array;

    /**
     * @param Node[] $parents
     * @param int $depth
     */
    public function fillChildren(array $parents, int $depth): void
    {
        if (!empty($parents)) {
            $parentIds = array_map(static fn (Node $node): int => $node->getId(), $parents);
            $parentByIds = array_combine($parentIds, $parents);

            if ($depth > 0) {
                foreach ($parents as $parent) {
                    $parent->initChildren();
                }
                $children = $this->getChildren($parentIds);
                $this->treeBuilder->fillChildren($children, $depth - 1);
                foreach ($children as $child) {
                    $parentByIds[$child->getParentId()]->addChild($child);
                }
            }
        };
    }
}