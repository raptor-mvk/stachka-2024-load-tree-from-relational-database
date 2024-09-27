<?php

namespace App\Service;

use App\Entity\Course;
use App\Model\Node;
use App\Model\NodeTypeEnum;
use Doctrine\ORM\EntityManagerInterface;

class CourseNodeResolver extends AbstractInternalNodeResolver
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getData(int $id): ?Node
    {
        return $this->mapCourseToNode($this->entityManager->getRepository(Course::class)->find($id));
    }

    protected function getChildren(array $parentIds): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $children = $qb->select('c')
                ->from(Course::class, 'c')
                ->where($qb->expr()->in('c.group', ':parentIds'))
                ->orderBy('c.position', 'ASC')
                ->setParameter('parentIds', $parentIds)
                ->getQuery()
                ->getResult();

        return array_map([$this, 'mapCourseToNode'], $children);
    }

    private function mapCourseToNode(?Course $course): ?Node
    {
        return $course === null ? null : new Node(
            $course->getId(),
            $course->getName(),
            NodeTypeEnum::Course->value,
            $course->getGroup()->getId(),
        );
    }
}