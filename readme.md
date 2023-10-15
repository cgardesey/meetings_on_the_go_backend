# Meetings on the Go Android App Backend

## Table of Contents
- [Introduction](#introduction)
- [Requirements](#requirements)
- [Installation](#installation)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [License](#license)

## Introduction

This backend is built using Laravel, a popular PHP framework, to provide robust and efficient API services for the [**Meetings on the Go** android app](https://github.com/cgardesey/MeetingsOnTheGo).

## Requirements

To run this Laravel application, you'll need the following software installed on your system:

- PHP (>= 7.4)
- Laravel (>= 5.x)
- MySQL (>= 8.0) or any other compatible database
- Composer (for dependency management)

## Installation

- Clone this repository to your local machine:
   ```shell
   git git@github.com:cgardesey/meetings_on_the_go_backend.git
   
- Change into the project directory:
   ```shell
   cd meetings_on_the_go_backend
- Install the project dependencies using Composer:
   ```shell
   Composer install
- Install JavaScript dependencies using npm:
   ```bash
      npm install  
- Create a copy of the .env.example file and rename it to .env:
  ```shell
  cp .env.example .env
- Generate a new application key:
  ```shell
   php artisan key:generate
- Configure your database settings in the .env file.
- Run the database migrations:
   ```shell
   php artisan migrate
- Start the development server:
   ```shell
   php artisan serve

- You should now be able to access the application at `http://localhost:8000`.


## Deployment
To deploy this application to a production server, follow these steps:
- Set up a production-ready web server (e.g., Nginx, Apache).
- Configure your web server to point to the public directory.
- Update the .env file with production-specific settings.
- Ensure your server meets the PHP and database requirements.

## Contributing
If you'd like to contribute to this project, please follow these steps:
- Fork the repository on GitHub.
- Create a new branch with a descriptive name.
- Commit your changes to the new branch.
- Push the branch to your forked repository.
- Submit a pull request to the original repository.

Please ensure that your code follows the project's coding standards and includes appropriate tests for any new functionality.

If you're looking to integrate with the android project, make sure to check out the repository corresponding to the [Meetings on the Go Android App](https://github.com/cgardesey/MeetingsOnTheGo) for detailed instructions.
## License
This project is open-source and available under the [MIT License](https://opensource.org/licenses/MIT). Feel free to use it as a reference or starting point for your own projects.


