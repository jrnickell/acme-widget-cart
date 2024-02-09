# Acme Widget Cart

## Prerequisites

Docker and Docker Compose must be running locally.

- [Docker](https://www.docker.com)
- [Docker Compose](https://docs.docker.com/compose)

## Development Environment

*Note*: DO NOT forget to run `bin/down` when finished to turn off the development environment.

### Clone the Repository

Move into your projects directory in your terminal and clone the repository:

$ `git clone git@github.com:jrnickell/acme-widget-cart.git`

Move into the project directory

$ `cd acme-widget-cart`

### Install Composer Dependencies

$ `bin/composer install`

### Bring Up the Environment

$ `bin/up`

### Run Test Suite

$ `bin/phpunit`

### Take Down Environment

$ `bin/down`
