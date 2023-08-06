Team management (test task Herihaja)
--
Please follow below steps to setup project:

- First you need to install docker and docker-compose from docker official site https://docs.docker.com/get-docker/. Once you have installed it then you can proceed further installation below.

- Clone repository using `git clone git@github.com:herihaja/testtask.git` command.

- Create `.env` file in root directory from `.env.example` file and set parameter values accordingly,
Or you can leave default values as they are.

- Run `docker-compose build`.

- Run `docker-compose up -d`.

Login into the container using this command:
  `docker-compose exec php bash`.

- Run `composer install`.

- Run `npm install`.

- Run `npm run dev`.

To Run unit test (to be ran inside php container)
- Run `php bin/phpunit`


You can access application on below urls:
* Projet url: `localhost:8008`
* PhpMyAdmin url: `localhost:8090`
