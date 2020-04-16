<?php

namespace TodoApp\APIBundle\Controller;

use TodoCore\Application\Task\Query;
use TodoCore\Application\Task\Command;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use TodoCore\Domain\Exception\TaskNotFoundException;
use TodoCore\Domain\Exception\TaskStartingException;
use TodoCore\Domain\Exception\TaskNameEmptyException;
use TodoCore\Domain\Exception\TaskCompletionException;
use TodoCore\Domain\Exception\TaskNameExistedException;
use TodoCore\Application\Task\Exception\TaskSavingException;

/**
 * @Route("/tasks", name="tasks_")
 */
class TaskController
{
    /**
     * Get Task by id
     *
     * @Route("/{id}", methods={"GET"}, name="get")
     *
     * @param Query $query
     *
     * @return JsonResponse
     */
    public function get($id, Query $query)
    {
        try {
            return new JsonResponse($query->getById($id), Response::HTTP_OK);
        } catch (TaskNotFoundException $exception) {
            return $this->getErrorResponse($exception);
        }
    }

    /**
     * Create Task
     *
     * @Route("/create", methods={"POST"}, name="create")
     *
     * @param Request $request
     * @param Command $command
     *
     * @return JsonResponse
     */
    public function create(Request $request, Command $command)
    {
        $name = $request->get('name') ?? '';

        try {
            $task = $command->createNewTask($name);
        } catch (TaskNameExistedException|TaskNameEmptyException|TaskSavingException $exception) {
            return $this->getErrorResponse($exception);
        }

        return new JsonResponse($task, Response::HTTP_OK);
    }

    /**
     * Get collection of Tasks
     *
     * @Route("/collection", methods={"GET"}, name="collection")
     *
     * @param Query $query
     *
     * @return JsonResponse
     */
    public function collection(Query $query)
    {
        return new JsonResponse(
            [
                'uncompleted' => $query->getUncompleted(),
                'completed' => $query->getCompleted()
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Complete task
     *
     * @Route("/{id}/complete", methods={"GET"}, name="complete")
     *
     * @return JsonResponse
     */
    public function complete($id, Command $command)
    {
        try {
            $task = $command->completeTask($id);
        } catch (TaskNotFoundException|TaskCompletionException|TaskSavingException $exception) {
            return $this->getErrorResponse($exception);
        }

        return new JsonResponse($task, Response::HTTP_OK);
    }

    /**
     * Start task
     *
     * @Route("/{id}/start", methods={"GET"}, name="start")
     *
     * @return JsonResponse
     */
    public function start($id, Command $command)
    {
        try {
            $task = $command->startTask($id);
        } catch (TaskNotFoundException|TaskStartingException|TaskSavingException $exception) {
            return $this->getErrorResponse($exception);
        }

        return new JsonResponse($task, Response::HTTP_OK);
    }

    /**
     * @param \Exception $exception
     * @return JsonResponse
     */
    private function getErrorResponse(\Exception $exception)
    {
        return new JsonResponse(['errors' => [$exception->getMessage()]], Response::HTTP_BAD_REQUEST);
    }
}