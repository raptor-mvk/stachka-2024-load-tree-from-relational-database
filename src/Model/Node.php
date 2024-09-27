<?php

namespace App\Model;

class Node implements \JsonSerializable
{
    /** @var Node[]|null  */
    private ?array $children = null;

    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly string $type,
        private readonly ?int $parentId
    ) {
    }

    public function initChildren(): void
    {
        if ($this->children === null) {
            $this->children = [];
        }
    }

    public function addChild(Node $child): void
    {
        $this->children[] = $child;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'children' => $this->children === null ? null :
                array_map(static fn(Node $node): array => $node->jsonSerialize(), $this->children),
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }
}