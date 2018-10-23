DOCKER_COMPOSE  = docker-compose

EXEC_PHP        = $(DOCKER_COMPOSE) exec -T php /entrypoint
EXEC_JS         = $(DOCKER_COMPOSE) exec -T node /entrypoint

SYMFONY         = $(EXEC_PHP) bin/console
COMPOSER        = $(EXEC_PHP) composer
YARN            = $(EXEC_JS) yarn

##
## Project
## -------
##
build:
	$(DOCKER_COMPOSE) build --pull

build-nocache:
	$(DOCKER_COMPOSE) build --pull --no-cache

start: ## Start the project
start:
	$(DOCKER_COMPOSE) up -d --remove-orphans --no-recreate

stop: ## Stop the project
	$(DOCKER_COMPOSE) stop

kill:
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

clean: ## Stop the project and remove generated files
clean: kill
	rm -rf .env vendor node_modules var/cache/* var/log/* public/build/*

reset: ## Stop and start a fresh install of the project
reset: kill install

install: ## Install and start the project
install: .env build start db assets

install-nocache: ## Install with no cache on docker and start the project
install-nocache: .env build-nocache start db assets

deploy: .env vendor assets-prod db-update sync-translation update-js-route
	rm -rf var/cache/*

no-docker:
	$(eval DOCKER_COMPOSE := \#)
	$(eval EXEC_PHP := )
	$(eval EXEC_JS := )

.PHONY: build kill install reset start stop clean no-docker deploy
##
## Utils
## -----
##

db: ## Reset the database and load fixtures
db: flush .env vendor
	-$(SYMFONY) doctrine:database:drop --if-exists --force
	-$(SYMFONY) doctrine:database:create --if-not-exists
	$(SYMFONY) doctrine:schema:update --no-interaction --force
	$(SYMFONY) doctrine:fixtures:load --no-interaction

db-update: ## Update database
db-update: flush .env vendor
	$(SYMFONY) doctrine:schema:update --no-interaction --force

db-validate-schema: ## Validate the doctrine ORM mapping
db-validate-schema: .env vendor
	$(SYMFONY) doctrine:schema:validate

update-js-route: ## Update js route
update-js-route: .env vendor
	$(SYMFONY) fos:js-routing:dump --format=json --target=public/build/admin/js/fos_js_routes.json

assets: ## Run Yarn to compile assets
assets: node_modules
	rm -rf public/build/*
	$(YARN) run build

assets-prod: ## Run Yarn to compile and minified assets
assets-prod: node_modules
	rm -rf public/build/*
	$(YARN) run build-prod

watch: ## Run Yarn in watch mode
watch: node_modules
	$(YARN) run watch

clear: ## clear cache
clear: .env vendor
	$(SYMFONY) cache:clear --env=dev

flush: ## Flush db
flush: .env vendor
	-$(SYMFONY) doctrine:cache:clear-query
	-$(SYMFONY) doctrine:cache:clear-metadata
	$(SYMFONY) doctrine:cache:clear-result

sync-translation: ## Synchronisation translation from Loco (https://localise.biz)
sync-translation: .env vendor
	$(SYMFONY) translation:download

console: ## Console symfony
console: .env vendor
	$(SYMFONY) $(filter-out $@,$(MAKECMDGOALS))

.PHONY: db assets watch clear flush console assets-prod update-js-route sync-translation

##
## Tests
## -----
##

test: ## Run unit and functional tests
test: tu tf

tu: ## Run unit tests
tu: vendor
	$(EXEC_PHP) ./vendor/bin/phpunit tests --exclude-group functional

tf: ## Run functional tests
tf: vendor
	$(EXEC_PHP) ./vendor/bin/phpunit tests --group functional

.PHONY: tests tu tf

.env: .env.dist
	@if [ -f .env ]; \
	then\
		echo '\033[1;41m/!\ The .env.dist file has changed. Please check your .env file (this message will not be displayed again).\033[0m';\
		touch .env;\
		exit 1;\
	else\
		echo cp .env.dist .env;\
		cp .env.dist .env;\
	fi
# rules based on files
composer.lock: composer.json
	$(COMPOSER) update --lock --no-scripts --no-interaction

vendor: composer.lock
	$(COMPOSER) install

node_modules: yarn.lock
	$(YARN) install
	@touch -c node_modules

yarn.lock: package.json
	$(YARN) upgrade

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help

##
## Quality assurance
## -----------------
##

QA        = docker run --rm -v `pwd`:/project mykiwi/phaudit:7.2
ARTEFACTS = var/artefacts

lint: ## Lints twig and yaml files
lint: lt ly

lt: vendor
	$(SYMFONY) lint:twig templates

ly: vendor
	$(SYMFONY) lint:yaml config

security: ## Check security of your dependencies (https://security.sensiolabs.org/)
security: vendor
	$(EXEC_PHP) ./vendor/bin/security-checker security:check

phploc: ## PHPLoc (https://github.com/sebastianbergmann/phploc)
	$(QA) phploc src/

pdepend: ## PHP_Depend (https://pdepend.org)
pdepend: artefacts
	$(QA) pdepend \
		--summary-xml=$(ARTEFACTS)/pdepend_summary.xml \
		--jdepend-chart=$(ARTEFACTS)/pdepend_jdepend.svg \
		--overview-pyramid=$(ARTEFACTS)/pdepend_pyramid.svg \
		src/

phpmd: ## PHP Mess Detector (https://phpmd.org)
	$(QA) phpmd src text .phpmd.xml

php_codesnifer: ## PHP_CodeSnifer (https://github.com/squizlabs/PHP_CodeSniffer)
	$(QA) phpcs -v --standard=.phpcs.xml src

phpcpd: ## PHP Copy/Paste Detector (https://github.com/sebastianbergmann/phpcpd)
	$(QA) phpcpd src

phpdcd: ## PHP Dead Code Detector (https://github.com/sebastianbergmann/phpdcd)
	$(QA) phpdcd src

phpmetrics: ## PhpMetrics (http://www.phpmetrics.org)
phpmetrics: artefacts
	$(QA) phpmetrics --report-html=$(ARTEFACTS)/phpmetrics src

php-cs-fixer: ## php-cs-fixer (http://cs.sensiolabs.org)
	bin/php-cs-fixer fix src --dry-run --using-cache=no --verbose --diff

apply-php-cs-fixer: ## apply php-cs-fixer fixes
	bin/php-cs-fixer fix src --using-cache=no --verbose --diff

twigcs: ## twigcs (https://github.com/allocine/twigcs)
	$(QA) twigcs lint templates

artefacts:
	mkdir -p $(ARTEFACTS)

.PHONY: lint lt ly phploc pdepend phpmd php_codesnifer phpcpd phpdcd phpmetrics php-cs-fixer apply-php-cs-fixer artefacts
