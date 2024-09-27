<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Column(type: 'bigint', unique: true, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $name;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $position;

    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'group')]
    private Collection $courses;

    public function __construct()
    {
        $this->clearCourses();
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

    /**
     * @return Course[]
     */
    public function getCourses(): array
    {
        return $this->courses->toArray();
    }

    public function addToCourses(Course $Course): void
    {
        if (!$this->courses->contains($Course)) {
            $this->courses->add($Course);
        }
    }

    public function clearCourses(): void
    {
        $this->courses = new ArrayCollection();
    }
}
