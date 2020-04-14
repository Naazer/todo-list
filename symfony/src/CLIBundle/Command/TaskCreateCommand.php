<?php

namespace TodoApp\CLIBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use TodoCore\Application\Task\Command as TaskCommand;
use Symfony\Component\Console\Output\OutputInterface;
use TodoCore\Domain\Exception\TaskNameEmptyException;
use TodoCore\Domain\Exception\TaskNameExistedException;
use TodoCore\Application\Task\Exception\TaskSavingException;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class TaskCreateCommand extends Command
{
    /**
     * @var TaskCommand
     */
    private $taskCommand;

    /**
     * TaskCreateCommand constructor.
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
            ->setName('todo-app:task:create')
            ->setDescription('Create Task')
            ->addArgument('name', InputArgument::REQUIRED, 'Task name');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     * @throws TaskNameEmptyException
     * @throws TaskNameExistedException
     * @throws TaskSavingException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $name = $input->getArgument('name');
        } catch (InvalidArgumentException $exception) {
            throw $exception;
        }
        $output->writeln(sprintf('<info>TodoApp: Task creation started with name <%s> ... </info>', $name));

        try {
            $task = $this->taskCommand->createNewTask($name);
            $output->writeln(sprintf('<options=bold>TodoApp: Task <fg=green>%s</> created with ID <%s></>', $task->getName(), $task->getId()));

        } catch (TaskNameExistedException|TaskNameEmptyException|TaskSavingException $exception) {
            $output->writeln(sprintf('<error>TodoApp ERROR type: %s</error>', get_class($exception)));
            $output->writeln(sprintf('<error>TodoApp ERROR message: %s</error>', $exception->getMessage()));
        }

        return 0;
    }
}