#!/usr/bin/env bash
# Containerized Pest test runner for TryPost.
#
# Builds a test image from the docker/Dockerfile `asset-build` stage (PHP 8.4
# + composer with dev deps + Node, source baked in, wayfinder helpers already
# generated), attaches it to the trypost compose network, and runs the full
# Feature+Unit Pest suite against the trypost-pgsql container using the
# trypost_test / analytics_test databases.
#
# Never touches the `trypost` app container or the `trypost`/`analytics`
# production databases: only trypost_test and analytics_test are used.
#
# The real trypost Postgres password is read server-side from the compose
# file and passed straight into the test container's environment; it is
# never echoed to stdout/stderr by this script.
set -euo pipefail

cd "$(dirname "$0")/.."

COMPOSE_FILE="/home/goo6i/docker/trypost/compose.prod.yaml"
NETWORK="trypost_default"
IMAGE="trypost-testbase"
PG_CONTAINER="trypost-pgsql"

DB_PASSWORD="$(grep -m1 '^\s*DB_PASSWORD:' "$COMPOSE_FILE" | sed -E 's/^[^:]+:\s*"?([^"[:space:]]+)"?\s*$/\1/')"
if [ -z "${DB_PASSWORD:-}" ]; then
    echo "ERROR: could not read DB_PASSWORD from $COMPOSE_FILE" >&2
    exit 1
fi

docker build --target asset-build -t "$IMAGE" -f docker/Dockerfile .

# Ensure trypost_test exists (idempotent, never touches the trypost DB itself
# beyond a catalog lookup).
EXISTS="$(docker exec "$PG_CONTAINER" psql -U trypost -d trypost -tAc \
    "SELECT 1 FROM pg_database WHERE datname = 'trypost_test'")"
if [ "$EXISTS" != "1" ]; then
    docker exec "$PG_CONTAINER" psql -U trypost -d trypost -c \
        "CREATE DATABASE trypost_test OWNER trypost;"
fi

# The asset-build stage bakes in a stub APP_KEY only so `artisan` can boot
# during the Vite/wayfinder build step; it is not a valid AES key length, so
# any test touching an `encrypted` cast (e.g. SocialAccount tokens) blows up
# with "Unsupported cipher or incorrect key length" unless we hand the test
# run a real generated key.
APP_KEY="base64:$(openssl rand -base64 32)"

RUN_ENV=(
    -e DB_CONNECTION=pgsql
    -e DB_HOST="$PG_CONTAINER"
    -e DB_PORT=5432
    -e DB_DATABASE=trypost_test
    -e DB_USERNAME=trypost
    -e DB_PASSWORD="$DB_PASSWORD"
    -e APP_ENV=testing
    -e APP_KEY="$APP_KEY"
    -e ANALYTICS_DB_DATABASE=analytics_test
    -e ANALYTICS_DB_USERNAME=trypost
    -e ANALYTICS_DB_PASSWORD="$DB_PASSWORD"
)

# Make sure trypost_test has schema. RefreshDatabase wraps each test in a
# transaction but does not create the schema on a database that has never
# been migrated, so migrate once up front (no-op on subsequent runs).
docker run --rm --network "$NETWORK" "${RUN_ENV[@]}" "$IMAGE" \
    php artisan migrate --database=pgsql --force

# Passport (OAuth2 API tokens) needs RSA keys under storage/; CI's
# .github/actions/setup-laravel generates them with the same command before
# every test run. Without this every Api/* test fails with
# `LogicException: Invalid key supplied` from league/oauth2-server, which is
# a runner-setup gap, not an app bug. The keys must be generated in the same
# container as the test run (each `docker run` is a fresh, unmounted
# filesystem), so this and the test invocation below are chained in one
# container via `sh -c`.
#
# NOTE: `php artisan test` reliably crashes here with a `zend_mm_heap
# corrupted` SIGABRT when it spawns the Pest binary as a Symfony Process
# subprocess (Alpine/musl container quirk, not an app bug: same failure with
# no test output at all, before any test runs). Invoking vendor/bin/pest
# directly runs the identical Pest/PHPUnit engine against the same
# phpunit.xml and is the reliable equivalent in this environment.
docker run --rm --network "$NETWORK" "${RUN_ENV[@]}" "$IMAGE" \
    sh -c 'php artisan passport:keys --force && php -d memory_limit=1G vendor/bin/pest --compact "$@"' sh "$@"
