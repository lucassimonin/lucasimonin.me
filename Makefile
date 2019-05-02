DOCKER_COMPOSE  = docker-compose

EXEC_FRONT      = $(DOCKER_COMPOSE) exec -T front
EXEC_PHP        = $(DOCKER_COMPOSE) exec -T language
EXEC_CERTBOT    = $(DOCKER_COMPOSE) exec -T certbot

CONSOLE         = $(EXEC_PHP) php bin/console
COMPOSER        = $(EXEC_PHP) composer
QA				= $(DOCKER_COMPOSE) exec -T quality
YARN         	= $(EXEC_FRONT) yarn


ifneq ("$(wildcard .env)","")
    include .env
    export $(shell sed 's/=.*//' .env)
endif

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
	$(CONSOLE) doctrine:database:drop --if-exists --force
	$(CONSOLE) doctrine:database:create --if-not-exists
	$(CONSOLE) doctrine:migrations:migrate --no-interaction --allow-no-migration
	$(CONSOLE) hautelook:fixtures:load --no-interaction

db-diff: ## create migration file
db-diff: flush .env vendor
	$(CONSOLE) doctrine:cache:clear-metadata
	$(CONSOLE) doctrine:migrations:diff --no-interaction

db-update: ## Update database
db-update: flush .env vendor
	$(CONSOLE) doctrine:cache:clear-metadata
	$(CONSOLE) doctrine:migrations:migrate --no-interaction --allow-no-migration

db-validate-schema: ## Validate the doctrine ORM mapping
db-validate-schema: .env vendor
	$(CONSOLE) doctrine:schema:validate

db-load-fixture: .env vendor
	$(CONSOLE) hautelook:fixtures:load

update-js-route: ## Update js route
update-js-route: .env vendor
	$(CONSOLE) fos:js-routing:dump --format=json --target=public/build/admin/js/fos_js_routes.json

assets: ## Run Yarn to compile assets
assets: node_modules
	rm -rf public/build/*
	$(YARN) run dev

assets-prod: ## Run Yarn to compile and minified assets
build-assets: node_modules
	rm -rf public/build/*
	$(YARN) run build

watch: ## Run Yarn in watch mode
watch: node_modules
	$(YARN) run watch

clear: ## clear cache
clear: .env vendor
	$(CONSOLE) cache:clear --env=dev

flush: ## Flush db
flush: .env vendor
	-$(CONSOLE) doctrine:cache:clear-query
	-$(CONSOLE) doctrine:cache:clear-metadata
	$(CONSOLE) doctrine:cache:clear-result

sync-translation: ## Synchronisation translation from Loco (https://localise.biz)
sync-translation: .env vendor
	$(CONSOLE) translation:download

console: ## Console symfony
console: .env vendor
	$(CONSOLE) $(filter-out $@,$(MAKECMDGOALS))

sync-ssl:
	$(EXEC_CERTBOT) openssl req -x509 -newkey rsa:4096 -keyout /srv/docker/nginx/cert/key.pem -out /srv/docker/nginx/cert/cert.pem -days 365 -nodes -subj "/C=FR/ST=Herault/L=Montpellier/O=/OU=Org/CN=$(APP_SERVER_NAME)"

new-certificate:
	$(EXEC_CERTBOT) certbot certonly --webroot  --config-dir /srv/docker/nginx/cert --work-dir /srv/docker/nginx/cert --logs-dir /srv/docker/nginx/cert -w /srv/public -n --agree-tos -m lsimonin2@gmail.com -d $(APP_SERVER_NAME)

renew-certificate:
	$(EXEC_CERTBOT) certbot renew --webroot --config-dir /srv/docker/nginx/cert --work-dir /srv/docker/nginx/cert --logs-dir /srv/docker/nginx/cert -w /srv/public

.PHONY: db assets watch clear flush console sync-ssl assets-prod update-js-route sync-translation

##
## Tests
## -----
##

test: ## Run unit and functional tests
test: tu tf

tu: ## Run unit tests
tu: vendor
	$(EXEC_PHP) bin/phpunit tests --color --exclude-group functional

tf: ## Run functional tests
tf: vendor
	$(EXEC_PHP) bin/phpunit tests --color --group functional

.PHONY: tests tu tf

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
ARTEFACTS = var/artefacts

lint: ## Lints twig and yaml files
lint: lt ly

lt: vendor
	$(CONSOLE) lint:twig templates

ly: vendor
	$(CONSOLE) lint:yaml config

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
	$(QA) php-cs-fixer fix src --dry-run --using-cache=no --verbose --diff

apply-php-cs-fixer: ## apply php-cs-fixer fixes
	$(QA) php-cs-fixer fix src --using-cache=no --verbose --diff

twigcs: ## twigcs (https://github.com/allocine/twigcs)
	$(QA) twigcs lint templates

artefacts:
	mkdir -p $(ARTEFACTS)

.PHONY: lint lt ly phploc pdepend phpmd php_codesnifer phpcpd phpdcd phpmetrics php-cs-fixer apply-php-cs-fixer artefacts
