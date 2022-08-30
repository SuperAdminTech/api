SuperAdmin REST API
===================

Welcome to the SuperAdmin REST API


# Development
[![codecov](https://codecov.io/gh/QbitArtifacts/caste/branch/master/graph/badge.svg?token=DXQI5GR8CM)](https://codecov.io/gh/SuperAdminTech/api)
[![Conventional Commits](https://img.shields.io/badge/Conventional%20Commits-1.0.0-yellow.svg)](https://conventionalcommits.org)

## Setup
### Install Docker
Install **docker** and **docker-compose** using the [official documentation](https://docs.docker.com/install/).
Make sure having added your user to the `docker` group to avoid trouble with permissions, see [docs](https://docs.docker.com/install/linux/linux-postinstall/)

### Run API with dependencies (MySQL, Mongodb, Node)
#### Start services
Using **GNU Make** (need `autotools` installed, recommended)
```
make dev
```
Using docker directly
```
docker-compose -f docker/dev/docker-compose.yml up --build
```
#### Stop/Down services
Using **GNU Make** (recommended)
```
make down
```

Using docker directly
```
docker-compose -f docker/dev/docker-compose.yml down
```

#### Run some command in the `api` container
to see the list of running containers use `make ps`

to run any command in the `api` container (recommended)
```
make shell api
```

Or using docker

to see the list of running containers use `docker ps`

to run any command in the `api` container
```
docker exec -it rec-api_api_1 bash
```

### Run API image only (troubleshooting)
Using **GNU Make** (recommended)
```
make debug api
```

Using docker directly
```
docker build . -f docker/dev/Dockerfile -t api-dev
docker run -it -v `pwd`:/api -u $UID:$UID api-dev <command>
```
note that this method launches a new container with the code mounted, so it will only work if the command affects only to filesystem, if it has something to do with the database it will fail because the container cannot communicate with the database (for example command `app/console doctrine:schema:update --force` will not work)

### Run API tests
Using **GNU Make** (recommended)
```
# run all tests
make test

# run all tests with coverage % report
make coverage
```

Using phpunit (must run previously `make shell` to have a project shell)
```
vendor/bin/phpunit
```
Integrating with your IDE (VisualStudio, PhpStorm, etc...)

see your IDE documentation to setup your php interpreter and phpunit environment, tested IDEs are PhpStorm.


#### Admin databases and test
the services started with docker-compose are available at localhost in different ports
* `API` is running on `localhost:8000`
* `API Docs` is running on `localhost:8000/docs`
* `PhpMyAdmin` instance is running on `localhost:8080`

the rest of services haven't got any port exposed to outside, but available inside the containers
* `mariadb` instance opens port `3306` to the container network
  - empty `root` password
  - database `api`
  - user `api`
  - password `api`

#### Troubleshooting
to deal with troubleshooting is possible to acces to the runningcontainer's shell using 
`docker exec -it dev_api_1 bash` (explained [above](#run-some-command-in-the-api-container)),
but if the container doesn't start (ie. missing dependencies), the method must be to executing
directly the built image using `docker run -it -v `pwd`:/api -u $UID:$UID rec-api-dev bash` (also
explained [above](#run-api-image-only)).

### Generate JWT key pair
JWT key pair is needed to sign and verify bearer tokens.

Private key is used to generate tokens when user does login, this key must be keep in a safe place and not share to
anyone.

Public keys to verify the token, so you need to provide ONLY this public key to other projects to have a successful
integration.

If you're using the API Platform distribution, you may run this from the project's root directory:
```sh
mkdir -p config/jwt
jwt_passhrase=$(grep "^JWT_PASSPHRASE=" .env | cut -f 2 -d=)
echo "$jwt_passhrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
echo "$jwt_passhrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout
```
This takes care of using the correct passphrase to encrypt the private key, and setting the correct permissions on the keys allowing the web server to read them.

### Create database schema
You need the base database schema to be created.

```shell
make shell api
app doctrine:schema:update --force
```

### Create first admin and application
You need to create at least one admin and application to make other projects be able to authenticate using SuperAdmin API.

```sh
make shell api
app app:bootstrap

 Creating first user and application
 Creating Application
 Name: Default Application
 Realm: default

 [OK] Done                                                                      

 Creating User
 Username: admin630e9310c635c
 Password: secret630e9310c635f

 [OK] Done                                                                      

 Creating Account
 Name: admin630e9310c635c

 [OK] Done                                                                      

 Creating Permission
 Grants: [ACCOUNT_MANAGER]

 [OK] Done                                                                      

 [OK] Success                                                                   

```

### Considerations and known issues
#### Token TTL (time to live)
If a tokens TTL is small, there is a risk of being expired whilst a user is in the middle of some operation. If token
expires it will reset everything, and user will need to redo what he was doing.

