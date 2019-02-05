# Dockerized Distributed System

This is a demo project, simulating a small distributed login and password recover system, using nodeJS as web app, basic php api on Slim framework and queue system with RabbitMQ inside a docker solution.

Requirments:
* NPM
* Docker [18.05.0-ce]
* DockerCompose [1.21.2]

## Installation

* make build
* make database
* make start

## Usage

### Start

* make start
* cd app
* npm i
* npm start

> Listen:
* App: http://localhost:3000
* Api: http://localhost:3001
* Consumer: http://localhost:5672

### Stop
* make stop

## Uninstall
* make remove