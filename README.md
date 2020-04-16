Todo list app
==============

This application was build by using DDD.


User Story
----------------------------------

"As a user, I want to have an ability to see a list of tasks for my day, so that I can do them one by one".

Acceptance criteria: 
 * User can add any count of tasks
 * Task name is unique
 * When user add task it moves to backlog
 * User can start (move status to in-progress) only one task
 * User can complete only task that was in-progress status

Installation
----------------------------------

1. Build the images: `docker-compose build`

2. Run the project: `docker-compose up -d`

3. Install Composer dependencies: `docker-compose exec php composer install --optimize-autoloader`

4. Run migrations `docker-compose exec php bin/console doctrine:migrations:migrate`


Project structure
----------------------------------

- todo-core
    - Application
    - Domain
    - Infrastructure
    - Tests
- symfony
    - src/CLIBundle
    - src/APIBundle
- docker
 

Command Line Interface Bundle
----------------------------------

Available commands:
 * Create task: `docker-compose exec php bin/console todo-app:task:create "Make a coffee"`
 * Get list of tasks with statuses collection: `docker-compose exec php bin/console todo-app:task:collection`
 * Start task: `docker-compose exec php bin/console todo-app:task:start 1`
 * Complete task: `docker-compose exec php bin/console todo-app:task:complete 1`
 
API Bundle
----------------------------------

Available endpoints:
 * Create task: `curl -d "name=Task1" -X POST http://todo.loc/tasks/create`
 * Get list of tasks with statuses collection: @todo
 * Start task: @todo
 * Complete task: @todo

Tests
----------------------------------

Run tests `docker-compose exec php bin/phpunit`