#!/bin/bash

# Load the variables
config="local"
source $(dirname "$0")/config.sh


sql="\list"
docker exec -i $docker \
    su - postgres -c 'psql -d '$dbname' -c "'$sql'"'

