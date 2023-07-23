#!/bin/bash

psql "$(awk -F "=" '/DB_URL/ {print $2}' app/.env)" < schema/schema.sql || echo "Ensure psql is installed and is in your path"
