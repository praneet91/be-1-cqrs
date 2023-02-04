<?php

declare(strict_types=1);

namespace App\API;

use App\Post\Domain\Post;
use App\Post\Domain\PostRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/posts/{post_id}', methods: ['GET'])]
class GetPostController
{
    public function __construct(
        private readonly PostRepository $repository
    ) {
    }

    public function __invoke(string $post_id): JsonResponse
    {
        Post::validateId($post_id);
        $post = $this->repository->find(Uuid::fromString($post_id));

        if (!$post) {
            return new JsonResponse('No data found', Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(
            [
                'post_id' => $post->getId(),
                'title' => $post->getTitle(),
                'summary' => $post->getSummary(),
                'description' => $post->getDescription(),
            ],
            Response::HTTP_OK,
        );
    }
}
