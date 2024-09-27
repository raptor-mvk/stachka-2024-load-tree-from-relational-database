<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Model\Node;
use App\Model\NodeTypeEnum;
use App\Model\SurrogateLevelId;
use Doctrine\ORM\EntityManagerInterface;

class LessonInUnitNodeResolver extends AbstractInternalNodeResolver
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getData(int $id): ?Node
    {
        /** @var Lesson $lesson */
        $lesson = $this->entityManager->getRepository(Lesson::class)->find($id);
        if ($lesson->getUnit() === null) {
            $lesson = null;
        }

        return $this->mapLessonToNode($lesson);
    }

    protected function getChildren(array $parentIds): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $children = $qb->select('l')->distinct()
                ->from(Lesson::class, 'l')
                ->where($qb->expr()->in('l.unit', ':units'))
                ->orderBy('l.position', 'ASC')
                ->setParameter('units', $parentIds)
                ->getQuery()
                ->getResult();

        return array_map([$this, 'mapLessonToNode'], $children);
    }

    private function mapLessonToNode(?Lesson $lesson): ?Node
    {
        return $lesson === null ? null : new Node(
            $lesson->getId(),
            $lesson->getName(),
            NodeTypeEnum::LessonInUnit->value,
            $lesson->getUnit()->getId(),
        );
    }
}