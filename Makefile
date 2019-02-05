build:
	docker-compose up -d --build

start:
	docker-compose up -d

database:
	docker exec -i dds-consumer mysql -uroot -proot < database.sql

stop:
	docker-compose stop

remove:
	docker-compose down --rmi 'all'