db:
	bash ./scripts/db.sh
init:
	docker exec php composer install --ignore-platform-reqs
install:
	bash ./scripts/install.sh
