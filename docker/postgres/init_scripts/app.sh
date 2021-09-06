#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" <<-EOSQL
    CREATE DATABASE app;
    \c app;
    GRANT CONNECT ON DATABASE app to app;
    GRANT ALL PRIVILEGES ON DATABASE app to app;
EOSQL

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" <<-EOSQL
    CREATE DATABASE app_test;
    \c app_test;
    GRANT CONNECT ON DATABASE app_test to app_test;
    GRANT ALL PRIVILEGES ON DATABASE app_test to app_test;
    GRANT CONNECT ON DATABASE app_test to app;
    GRANT ALL PRIVILEGES ON DATABASE app_test to app;
EOSQL

echo "host app app all md5" >> "$PGDATA/pg_hba.conf"
echo "host app_test app all md5" >> "$PGDATA/pg_hba.conf"
echo "host app_test app_test all md5" >> "$PGDATA/pg_hba.conf"