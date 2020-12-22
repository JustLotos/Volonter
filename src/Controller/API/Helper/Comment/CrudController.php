<?php

declare(strict_types=1);

namespace App\Controller\API\Helper\Comment;

use App\Controller\ControllerHelper;
use App\Domain\Helper\Security\CommentVoter;
use App\Domain\Helper\Comment\Entity\Comment;
use App\Domain\Helper\Comment\UseCase\Create\Command as CreateCommand;
use App\Domain\Helper\Comment\UseCase\Create\Handler as CreateHandler;
use App\Domain\Helper\Comment\UseCase\Remove\Handler as RemoveHandler;
use App\Domain\Helper\Comment\UseCase\Update\Command as UpdateCommand;
use App\Domain\Helper\Comment\UseCase\Update\Handler as UpdateHandler;
use App\Domain\Helper\Task\Entity\Types\Id;
use App\Domain\Helper\Comment\CommentRepository;
use App\Domain\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/comment") */
class CrudController extends AbstractController
{
    use ControllerHelper;

    /** @Route("/cget/{taskId}/", name="getAllCommentsByTask", methods={"GET"}) */
    public function getAllCommentsByTask(CommentRepository $repository, string $taskId) : Response
    {
        $comments = $repository->getAllByTask(new Id($taskId));
        return  $this->response($this->serializer->serialize($comments, Comment::GROUP_SIMPLE));
    }

    /** @Route("/get/{id}/", name="getComment", methods={"GET"}) */
    public function getComment(Comment $comment) : Response
    {
        return  $this->response($this->serializer->serialize($comment, Comment::GROUP_SIMPLE));
    }

    /** @Route("/new/", name="createNewComment", methods={"POST"}) */
    public function create(Request $request, CreateHandler $handler): Response
    {
        /** @var CreateCommand $command */
        $command = $this->serializer->deserialize($request, CreateCommand::class);
        $this->validator->validate($command);

        /** @var User $user */
        $user = $this->getUser();
        $comment = $handler->handle($user, $command);
        return $this->response($this->serializer->serialize($comment, Comment::GROUP_SIMPLE));
    }

    /** @Route("/update/{id}/", name="updateComment", methods={"PUT"}) */
    public function update(Request $request, Comment $comment, UpdateHandler $handler): Response
    {
        $this->denyAccessUnlessGranted(CommentVoter::EDIT, $comment, CommentVoter::NOT_FOUND_MESSAGE);
        /** @var UpdateCommand $command */
        $command = $this->serializer->deserialize($request, UpdateCommand::class);
        $this->validator->validate($command);
        $handler->handle($comment, $command);
        return $this->response($this->getSimpleSuccessResponse());
    }

    /** @Route("/remove/{id}/", name="deleteComment", methods={"POST"}) */
    public function remove(Comment $comment, RemoveHandler $handler): Response
    {
        $this->denyAccessUnlessGranted(CommentVoter::EDIT, $comment, CommentVoter::NOT_FOUND_MESSAGE);
        $handler->handle($comment);
        return $this->response($this->getSimpleSuccessResponse());
    }
}
