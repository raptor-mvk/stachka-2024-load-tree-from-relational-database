<?php

namespace App\Service;

use App\Entity\Group;
use App\Model\Node;
use App\Model\NodeTypeEnum;
use Doctrine\ORM\EntityManagerInterface;

class GroupNodeResolver extends AbstractInternalNodeResolver
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getData(int $id): ?Node
    {
        return $this->mapGroupToNode($this->entityManager->getRepository(Group::class)->find($id));
    }

    protected function getChildren(array $parentIds): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $groups = $qb->select('g')
            ->from(Group::class, 'g')
            ->orderBy('g.position', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map([$this, 'mapGroupToNode'], $groups);
    }

    private function mapGroupToNode(?Group $group): ?Node
    {
        return $group === null ? null : new Node(
            $group->getId(),
            $group->getName(),
            NodeTypeEnum::Group->value,
            -1 // default id for Root entity
        );
    }
}