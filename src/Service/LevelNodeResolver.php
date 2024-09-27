<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Model\Node;
use App\Model\NodeTypeEnum;
use App\Model\SurrogateLevelId;
use Doctrine\ORM\EntityManagerInterface;

class LevelNodeResolver extends AbstractInternalNodeResolver
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getData(int $id): ?Node
    {
        return $this->mapLevelToNode(SurrogateLevelId::fromNodeId($id));
    }

    protected function getChildren(array $parentIds): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $children = $qb->select('l.level', 'IDENTITY(l.course) as course')->distinct()
                ->from(Lesson::class, 'l')
                ->where($qb->expr()->in('l.course', ':courses'))
                ->orderBy('l.level', 'ASC')
                ->setParameter('courses', $parentIds)
                ->getQuery()
                ->getResult();

        return array_map([$this, 'mapLevelToNode'], array_map([SurrogateLevelId::class, 'fromDatabase'], $children));
    }

    private function mapLevelToNode(?SurrogateLevelId $surrogateLevelId): ?Node
    {
        return $surrogateLevelId === null ? null : new Node(
            $surrogateLevelId->toNodeId(),
            Lesson::LEVELS[$surrogateLevelId->level],
            NodeTypeEnum::Level->value,
            $surrogateLevelId->courseId,
        );
    }
}