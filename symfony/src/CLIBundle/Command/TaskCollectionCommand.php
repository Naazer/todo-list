<?php

namespace TodoApp\CLIBundle\Command;

use TodoCore\Domain\Entity\Task;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use TodoCore\Application\Task\Query as TaskQuery;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TodoCore\Domain\Exception\TaskNameEmptyException;
use TodoCore\Domain\Exception\TaskNameExistedException;
use TodoCore\Application\Task\Exception\TaskSavingException;

class TaskCollectionCommand extends Command
{
    /**
     * @var TaskQuery
     */
    private $taskQuery;

    /**
     * TaskCollectionCommand constructor.
     * @param TaskQuery $taskQuery
     */
    public function __construct(TaskQuery $taskQuery)
    {
        // DI TaskCommand
        $this->taskQuery = $taskQuery;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setName('todo-app:task:collection')
            ->setDescription('Get collection of tasks')
        ;
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
        $output->writeln('<info>TodoApp: Getting collection of tasks started ... </info>');

        // Get uncompleted
        $uncompletedTasks = $this->taskQuery->getUncompleted();
        if (count($uncompletedTasks)) {
            $output->writeln('<options=bold>TodoApp: Remaining tasks:</>');
            $table = new Table($output);
            $table->setHeaders(['ID', 'Name', 'Status']);
            foreach ($uncompletedTasks as $uncompletedTask) {
                /** @var Task $uncompletedTask */
                $statusRow = $uncompletedTask->isInProgress() ? '<fg=green>%s</>' : '%s';
                $table->addRow([$uncompletedTask->getId(), $uncompletedTask->getName(), sprintf($statusRow, $uncompletedTask->getStatus())]);
            }
            $table->render();
        }

        // Get completed
        $completedTasks = $this->taskQuery->getCompleted();
        if (count($completedTasks)) {
            $output->writeln('<options=bold>TodoApp: Completed tasks:</>');
            foreach ($completedTasks as $completedTask) {
                /** @var Task $completedTask */
                $msg = sprintf('[%s] <fg=blue>%s</> -> done', $completedTask->getId(), $completedTask->getName());
                $output->writeln($msg);
            }
        }

        return 0;
    }
}