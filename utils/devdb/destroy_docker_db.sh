#!/bin/bash

# Get the variables
config='local'
source $(dirname "$0")/config.sh


echo_title "Destroing docker $docker"

docker stop $docker && docker rm $docker