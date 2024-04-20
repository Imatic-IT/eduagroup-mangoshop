#!/usr/bin/env bash
set -euo pipefail

php -n -c ./tests/php.ini "$@"
