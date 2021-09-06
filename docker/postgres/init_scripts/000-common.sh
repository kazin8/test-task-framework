#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" <<-EOSQL
    CREATE USER app PASSWORD 'app';
    CREATE USER app_test PASSWORD 'app_test';
EOSQL

echo "host all $POSTGRES_USER all md5" >> "$PGDATA/pg_hba.conf"
echo "host all app all md5" >> "$PGDATA/pg_hba.conf"