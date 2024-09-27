<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Task
{
    #[ORM\Column(type: 'bigint', unique: true, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $name;

    #[ORM\OneToMany(targetEntity: LessonTask::class, mappedBy: 'task')]
    private Collection $lessonTasks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return LessonTask[]
     */
    public function getLessonTasks(): array
    {
        return $this->lessonTasks->toArray();
    }

    public function addToLessonTasks(LessonTask $LessonTask): void
    {
        if (!$this->lessonTasks->contains($LessonTask)) {
            $this->lessonTasks->add($LessonTask);
        }
    }

    public function clearLessonTasks(): void
    {
        $this->lessonTasks = new ArrayCollection();
    }
}
