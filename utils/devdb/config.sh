
local_host="localhost"
local_user="local"
local_pass="local"
local_dbname="localfichaje"
local_dbport="5436"
local_docker="fichajedb"

# You can have secondary configs
local2_host=""
local2_user=""
local2_dbname=""
local2_dbport=""



function echo_title {

    echo "================================================="
    echo " $1"
    echo "================================================="
    
}

# Get the variables
host_variable="${config}_host"
host=${!host_variable}
user_variable="${config}_user"
user=${!user_variable}
pass_variable="${config}_pass"
pass=${!pass_variable}
dbname_variable="${config}_dbname"
dbname=${!dbname_variable}
dbport_variable="${config}_dbport"
dbport=${!dbport_variable}
docker_variable="${config}_docker"
docker=${!docker_variable}

echo_title "Using config $config"
echo "* host: $host"
echo "* dbport: $dbport"
echo "* dbname: $dbname"
echo "* user: $user"
echo "* pass: $pass"
echo "* docker: $docker"
