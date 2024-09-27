<?php

namespace App\Service;

use App\Entity\Unit;
use App\Model\Node;
use App\Model\NodeTypeEnum;
use App\Model\SurrogateLevelId;
use Doctrine\ORM\EntityManagerInterface;

class UnitNodeResolver extends AbstractInternalNodeResolver
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getData(int $id): ?Node
    {
        return $this->mapUnitToNode($this->entityManager->getRepository(Unit::class)->find($id));
    }

    protected function getChildren(array $parentIds): array
    {
        $surrogateLevelIds = array_map([SurrogateLevelId::class, 'fromNodeId'], $parentIds);
        $levels = array_unique(array_map(static fn (SurrogateLevelId $surrogateLevelId): int => $surrogateLevelId->level, $surrogateLevelIds));
        $courseIds = array_unique(array_map(static fn (SurrogateLevelId $surrogateLevelId): int => $surrogateLevelId->courseId, $surrogateLevelIds));
        $qb = $this->entityManager->createQueryBuilder();
        $children = $qb->select('u')->distinct()
                ->from(Unit::class, 'u')
                ->where($qb->expr()->in('u.level', ':levels'))
                ->andWhere($qb->expr()->in('u.course', ':courseIds'))
                ->orderBy('u.position', 'ASC')
                ->setParameter('levels', $levels)
                ->setParameter('courseIds', $courseIds)
                ->getQuery()
                ->getResult();

        return array_map([$this, 'mapUnitToNode'], $children);
    }

    private function mapUnitToNode(?Unit $unit): ?Node
    {
        return $unit === null ? null : new Node(
            $unit->getId(),
            $unit->getName(),
            NodeTypeEnum::Unit->value,
            (new SurrogateLevelId($unit->getLevel(), $unit->getCourse()->getId()))->toNodeId(),
        );
    }
}