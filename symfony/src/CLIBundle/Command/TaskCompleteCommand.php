<?php

namespace TodoApp\CLIBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use TodoCore\Domain\Exception\TaskCompletionException;
use TodoCore\Domain\Exception\TaskStartingException;
use TodoCore\Domain\Exception\TaskNotFoundException;
use TodoCore\Application\Task\Command as TaskCommand;
use Symfony\Component\Console\Output\OutputInterface;
use TodoCore\Domain\Exception\TaskNameEmptyException;
use TodoCore\Domain\Exception\TaskNameExistedException;
use TodoCore\Application\Task\Exception\TaskSavingException;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class TaskCompleteCommand extends Command
{
    /**
     * @var TaskCommand
     */
    private $taskCommand;

    /**
     * TaskCompleteCommand constructor.
     * @param TaskCommand $taskCommand
     */
    public function __construct(TaskCommand $taskCommand)
    {
        // DI TaskCommand
        $this->taskCommand = $taskCommand;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setName('todo-app:task:complete')
            ->setDescription('Complete Task')
            ->addArgument('id', InputArgument::REQUIRED, 'Task ID');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     * @throws TaskNotFoundException
     * @throws TaskStartingException
     * @throws TaskSavingException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $id = $input->getArgument('id');
        } catch (InvalidArgumentException $exception) {
            throw $exception;
        }
        $output->writeln(sprintf('<info>TodoApp: Task completing with id <%s> ... </info>', $id));

        try {
            $task = $this->taskCommand->completeTask($id);
            $output->writeln(sprintf('<options=bold>TodoApp: Task <fg=green>%s</> completed</>', $task->getName()));

        } catch (TaskNotFoundException|TaskCompletionException|TaskSavingException $exception) {
            $output->writeln(sprintf('<error>TodoApp ERROR type: %s</error>', get_class($exception)));
            $output->writeln(sprintf('<error>TodoApp ERROR message: %s</error>', $exception->getMessage()));
        }

        return 0;
    }
}