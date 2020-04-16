<?php

namespace TodoApp\APIBundle\Controller;

use TodoCore\Application\Task\Command;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use TodoCore\Domain\Exception\TaskNameEmptyException;
use TodoCore\Domain\Exception\TaskNameExistedException;
use TodoCore\Application\Task\Exception\TaskSavingException;

/**
 * @Route("/tasks", name="tasks_")
 */
class TaskController
{
    /**
     * Create Task
     *
     * @Route("/create", methods={"POST"}, name="create")
     *
     * @param Request $request
     * @param Command $command
     *
     * @return mixed
     */
    public function create(Request $request, Command $command)
    {
        $name = $request->get('name');

        $errors = [];
        try {
            $task = $command->createNewTask($name);
        } catch (TaskNameExistedException|TaskNameEmptyException|TaskSavingException $exception) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($task, Response::HTTP_OK);
    }
}