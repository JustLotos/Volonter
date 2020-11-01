<?php

namespace App\Domain\Helper\Comment\UseCase\Update;


use App\Domain\Helper\Comment\Entity\Comment;
use App\Service\FlushService;

class Handler
{
    private $flushService;

    public function __construct(FlushService $flushService)
    {
        $this->flushService = $flushService;
    }

    public function handle(Comment $comment, Command $command): Comment {
        $comment->update($command);
        $this->flushService->flush();
        return $comment;
    }
}