<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Lesson
{
    public const LEVELS = [
        1 => 'Pre-Intermediate',
        2 => 'Intermediate',
        3 => 'Upper-Intermediate',
    ];

    #[ORM\Column(type: 'bigint', unique: true, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $name;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $position;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $level;

    #[ORM\ManyToOne(targetEntity: Unit::class, inversedBy: 'lessons')]
    #[ORM\JoinColumn(name: 'unit_id', referencedColumnName: 'id', nullable: true)]
    private ?Unit $unit;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'lessons')]
    #[ORM\JoinColumn(name: 'course_id', referencedColumnName: 'id', nullable: true)]
    private ?Course $course;

    #[ORM\OneToMany(targetEntity: LessonTask::class, mappedBy: 'lesson')]
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

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): void
    {
        $this->unit = $unit;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): void
    {
        $this->course = $course;
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
