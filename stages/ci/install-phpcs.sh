#!/usr/bin/env bash
set -euo pipefail

bash stages/ci/install-composer.sh

[[ -d tools/coding-standard ]] || bash stages/ci/run-php.sh tools/composer.phar create-project nette/coding-standard --no-interaction --no-progress tools/coding-standard
