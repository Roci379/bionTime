#!/bin/bash

# Load the variables
config="local"
source $(dirname "$0")/config.sh


# Check number of arguments
if [ -z "$1" ]
then
	echo "Usage: ./restore_localdb.sh BACKUP_FILE"
    exit 1
fi

# Name of the destination
dumpfile=$1

# Dump with pg_dump's custom dump format
echo_title "Restoring dump '$dumpfile' of DB '$dbname' in $host:$dbport"

echo "Restoring as postgres user..."
docker exec -i $docker \
	su - postgres -c "pg_restore --verbose --no-owner --dbname=$dbname" <  $dumpfile

if [ $? -eq 0 ]; then
    echo "."
else
    echo "Failed"
    exit 1
fi

if [ $? -eq 0 ]; then
    echo "Restore finished"
else
    echo "Restore failed"
    exit 1
fi
