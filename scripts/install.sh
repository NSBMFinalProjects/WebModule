#!/bin/bash

printf ": "
read -r package

docker exec php composer require "$package" --ignore-platform-reqs
