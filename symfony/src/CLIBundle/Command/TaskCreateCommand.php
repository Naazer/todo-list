<?php

namespace TodoApp\CLIBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use TodoCore\Application\Task\Command as TaskCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use TodoCore\Application\Task\Exception\TaskSavingException;
use TodoCore\Domain\Exception\TaskNameEmptyException;
use TodoCore\Domain\Exception\TaskNameExistedException;

class TaskCreateCommand extends ContainerAwareCommand
{

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
        $output->writeln(sprintf('TodoApp: Task creation started with name <%s>', $name));

        try {
            $task = $this->getContainer()->get(TaskCommand::class)->createNewTask($name);
            $output->writeln(sprintf('TodoApp: Task created with ID <%s>', $task->getId()));

        } catch (TaskNameExistedException|TaskNameEmptyException|TaskSavingException $exception) {
            $output->writeln(sprintf('TodoApp ERROR type: <%s>', get_class($exception)));
            $output->writeln(sprintf('TodoApp ERROR message: <%s>', get_class($exception->getMessage())));
            throw $exception;
        }
    }
}