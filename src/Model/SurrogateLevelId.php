<?php

namespace App\Model;

class SurrogateLevelId
{
    private const COEFF = 10;

    public function __construct(
        public readonly int $level,
        public readonly int $courseId,
    ) {
    }

    public static function fromNodeId(int $id): self
    {
        return new self($id % self::COEFF, $id / self::COEFF);
    }

    public static function fromDatabase(array $data): self
    {
        return new self($data['level'], $data['course']);
    }

    public function toNodeId(): int
    {
        return $this->courseId * self::COEFF + $this->level;
    }
}