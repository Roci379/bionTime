#!/bin/bash

# Load the variables
config='local'
source $(dirname "$0")/config.sh

docker exec -it --env PGPASSWORD="$pass" $docker  /bin/bash -c "psql -h localhost -U $user $dbname"
