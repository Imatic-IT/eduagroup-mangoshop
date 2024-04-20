#!/usr/bin/env bash
set -euo pipefail

bash stages/ci/install-composer.sh

[[ -d tools/parallel-lint ]] || bash stages/ci/run-php.sh tools/composer.phar create-project jakub-onderka/php-parallel-lint --no-interaction --no-progress tools/parallel-lint
