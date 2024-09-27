<?php

namespace App\Service;

use App\Entity\LessonTask;
use App\Model\Node;
use App\Model\NodeTypeEnum;
use Doctrine\ORM\EntityManagerInterface;

class TaskNodeResolver extends AbstractInternalNodeResolver
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly NodeTypeEnum $nodeType,
    ) {
    }

    public function getData(int $id): ?Node
    {
        /** @var LessonTask $lessonTask */
        $lessonTask = $this->entityManager->getRepository(LessonTask::class)->find($id);
        if (($this->nodeType === NodeTypeEnum::TaskInLessonInLevel && $lessonTask->getLesson()->getUnit() !== null) ||
            ($this->nodeType === NodeTypeEnum::TaskInLessonInUnit && $lessonTask->getLesson()->getUnit() === null)) {
            $lessonTask = null;
        }

        return $this->mapLessonTaskToNode($lessonTask);
    }

    protected function getChildren(array $parentIds): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $children = $qb->select('lt')->distinct()
                ->from(LessonTask::class, 'lt')
                ->where($qb->expr()->in('lt.lesson', ':lessons'))
                ->orderBy('lt.position', 'ASC')
                ->setParameter('lessons', $parentIds)
                ->getQuery()
                ->getResult();

        return array_map([$this, 'mapLessonTaskToNode'], $children);
    }

    private function mapLessonTaskToNode(?LessonTask $lessonTask): ?Node
    {
        return $lessonTask === null ? null : new Node(
            $lessonTask->getId(), // id should be unique, so we use id of intermediate entity instead of target entity
            $lessonTask->getTask()->getName(),
            $this->nodeType->value,
            $lessonTask->getLesson()->getId(),
        );
    }
}