#!/bin/bash

# Check number of arguments
# Load the variables
#config="local"
config=${1:-"local"} 
source $(dirname "$0")/config.sh


# Name of the dumpfile
timestamp=$(date +%Y%m%d-%H%M)
dumpfile="${config}_${timestamp}.dump"


# Dump with pg_dump's custom dump format
echo "*************************************************************************"
echo "* Creating dump '$dumpfile' of DB '$dbname' in dev: $host:$dbport"
echo "*************************************************************************"

echo "Connecting to $user@$host:$dbname"

docker exec -i $docker \
	su - postgres -c "pg_dump --verbose --format=custom --dbname=$dbname" > $dumpfile

if [ $? -eq 0 ]; then
    echo "Backup finished: $dumpname"
else
    echo "Backup failed"
	rm $dumpname
	exit 1
fi

