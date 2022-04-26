
# Dockerized database

This set of scripts are used to build a dockerized version of a database.
Initially designed for the multiportal database but modified for the proposal portal.

## Config

Set the appropiate values in `config.sh`.


## Create the DB

Call `./create_docker_db.sh`

Check it is working with `docker ps`.


## Restore the dump of the real DB

Call `./restore_localdb.sh portal_XXXX.dump`


## Remove the DB

Call `./destroy_docker_db.sh`


## psql

Call `./psql.sh`