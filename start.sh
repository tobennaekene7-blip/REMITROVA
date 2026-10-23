#!/usr/bin/env bash
#
# start.sh — container start command for Railway (see nixpacks.toml).
#
# 1. Run migrations. If they fail the container exits (set -e), so a
#    broken schema shows up as a failed deploy instead of 500s.
# 2. Start a queue worker in the background (deposit webhooks are
#    processed by App\Jobs\ProcessPersistentAccountDeposit). It is
#    wrapped in a loop so it comes back if it exits or crashes.
# 3. Serve the app in the foreground on $PORT, which keeps the
#    container alive.

set -e

php artisan migrate --force

(
  while true; do
    php artisan queue:work --sleep=3 --tries=3 --max-time=3600 || true
    sleep 2
  done
) &

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
