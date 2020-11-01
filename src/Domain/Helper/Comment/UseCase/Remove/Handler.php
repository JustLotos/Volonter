<?php

namespace App\Domain\Helper\Comment\UseCase\Remove;

use App\Domain\Helper\Comment\CommentRepository;
use App\Domain\Helper\Comment\Entity\Comment;
use App\Service\FlushService;

class Handler
{
    private $flushService;
    private $commentRepository;

    public function __construct(
        CommentRepository $commentRepository,
        FlushService $flushService
    ){
        $this->flushService = $flushService;
        $this->commentRepository = $commentRepository;
    }

    public function handle(Comment $comment): Comment {
        $this->commentRepository->remove($comment);
        $this->flushService->flush();
        return $comment;
    }
}