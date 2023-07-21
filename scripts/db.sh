#!/bin/bash

usql "$(awk -F "=" '/DB_URL/ {print $2}' app/.env)" || echo "Cannot connect to the database"
