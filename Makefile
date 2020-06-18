
STACK := caste

dev:
	@docker-compose -f docker/dev/docker-compose.yml -p $(STACK) up --build

down:
	@docker-compose -f docker/dev/docker-compose.yml -p $(STACK) down

ps:
	@docker-compose -f docker/dev/docker-compose.yml -p $(STACK) ps

shell:
	@docker-compose -f docker/dev/docker-compose.yml -p $(STACK) exec $(word 2,$(MAKECMDGOALS)) bash

debug-shell:
	docker-compose -f docker/dev/docker-compose.yml -p $(STACK) run api bash

%:
	@: