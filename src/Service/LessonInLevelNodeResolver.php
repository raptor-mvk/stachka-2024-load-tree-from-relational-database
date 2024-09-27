<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Model\Node;
use App\Model\NodeTypeEnum;
use App\Model\SurrogateLevelId;
use Doctrine\ORM\EntityManagerInterface;

class LessonInLevelNodeResolver extends AbstractInternalNodeResolver
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getData(int $id): ?Node
    {
        /** @var Lesson $lesson */
        $lesson = $this->entityManager->getRepository(Lesson::class)->find($id);
        if ($lesson->getUnit() !== null) {
            $lesson = null;
        }

        return $this->mapLessonToNode($lesson);
    }

    protected function getChildren(array $parentIds): array
    {
        $surrogateLevelIds = array_map([SurrogateLevelId::class, 'fromNodeId'], $parentIds);
        $levels = array_unique(array_map(static fn (SurrogateLevelId $surrogateLevelId): int => $surrogateLevelId->level, $surrogateLevelIds));
        $courseIds = array_unique(array_map(static fn (SurrogateLevelId $surrogateLevelId): int => $surrogateLevelId->courseId, $surrogateLevelIds));
        $qb = $this->entityManager->createQueryBuilder();
        $children = $qb->select('l')->distinct()
                ->from(Lesson::class, 'l')
                ->where($qb->expr()->in('l.level', ':levels'))
                ->andWhere($qb->expr()->in('l.course', ':courseIds'))
                ->andWhere($qb->expr()->isNull('l.unit'))
                ->orderBy('l.position', 'ASC')
                ->setParameter('levels', $levels)
                ->setParameter('courseIds', $courseIds)
                ->getQuery()
                ->getResult();

        return array_map([$this, 'mapLessonToNode'], $children);
    }

    private function mapLessonToNode(?Lesson $lesson): ?Node
    {
        return $lesson === null ? null : new Node(
            $lesson->getId(),
            $lesson->getName(),
            NodeTypeEnum::LessonInLevel->value,
            (new SurrogateLevelId($lesson->getLevel(), $lesson->getCourse()->getId()))->toNodeId(),
        );
    }
}