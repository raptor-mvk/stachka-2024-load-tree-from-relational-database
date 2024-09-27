<?php

namespace App\Controller\Web\GetPath\v1;

use App\Model\Node;
use App\Model\NodeTypeEnum;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class Controller
{
    public function __construct(private readonly Manager $manager)
    {
    }

    #[Route(path: '/api/v1/get-path')]
    public function __invoke(#[MapQueryParameter] NodeTypeEnum $node, #[MapQueryParameter] int $id): Response
    {
        return new JsonResponse(
            array_map(
                static fn (Node $node): array => $node->jsonSerialize(),
                $this->manager->getPath($node, $id),
            )
        );
    }
}