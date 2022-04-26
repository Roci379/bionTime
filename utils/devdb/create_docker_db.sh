#!/bin/bash


# Get the variables
config='local'
source $(dirname "$0")/config.sh


echo_title "Creating docker $docker with DB $dbname for $user"

docker run --name "$docker" --publish $dbport:5432 -d \
--env POSTGRES_USER="$user" \
--env POSTGRES_DB="$dbname" \
--env POSTGRES_PASSWORD="$pass" \
--env ALLOW_IP_RANGE="0.0.0.0/0" \
--restart unless-stopped \
postgres:13
