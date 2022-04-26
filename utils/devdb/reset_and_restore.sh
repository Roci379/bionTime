#!/bin/bash


# Check number of arguments
if [ -z "$1" ]
then
	echo "Usage: ./reset_and_restore.sh BACKUP_FILE"
    exit 1
fi

# Name of the destination
dumpfile=$1

#
# Reset the docker container
#

echo "Reset docker"
./destroy_docker_db.sh && ./create_docker_db.sh

if [ $? -eq 0 ]; then
    echo "Reset docker db completed"
else
    echo "Reset docker db failed"
    exit 1
fi

#
# Wait
#
sleep 10

#
# Restore
#
./restore_localdb.sh $dumpfile



