<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Course
{
    #[ORM\Column(type: 'bigint', unique: true, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $name;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $position;

    #[ORM\ManyToOne(targetEntity: Group::class, inversedBy: 'courses')]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id', nullable: false)]
    private Group $group;

    #[ORM\OneToMany(targetEntity: Lesson::class, mappedBy: 'course')]
    private Collection $lessons;

    #[ORM\OneToMany(targetEntity: Unit::class, mappedBy: 'course')]
    private Collection $units;

    public function __construct()
    {
        $this->clearLessons();
        $this->clearUnits();
    }

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

    public function getGroup(): Group
    {
        return $this->group;
    }

    public function setGroup(Group $group): void
    {
        $this->group = $group;
    }

    /**
     * @return Lesson[]
     */
    public function getLessons(): array
    {
        return $this->lessons->toArray();
    }

    public function addToLessons(Lesson $lesson): void
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
        }
    }

    public function clearLessons(): void
    {
        $this->lessons = new ArrayCollection();
    }

    /**
     * @return Unit[]
     */
    public function getUnits(): array
    {
        return $this->units->toArray();
    }

    public function addToUnits(Unit $unit): void
    {
        if (!$this->units->contains($unit)) {
            $this->units->add($unit);
        }
    }

    public function clearUnits(): void
    {
        $this->units = new ArrayCollection();
    }
}
