#!/bin/bash

# Delete all stopped containers
docker ps -q -f status=exited | xargs --no-run-if-empty docker rm
# Delete all dangling (unused) images
docker images -q -f dangling=true | xargs --no-run-if-empty docker rmi
