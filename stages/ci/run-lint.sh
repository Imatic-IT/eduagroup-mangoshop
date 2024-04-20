#!/usr/bin/env bash
set -euo pipefail

bash stages/ci/install-lint.sh

bash stages/ci/run-php.sh tools/parallel-lint/parallel-lint.php -e php,phpt src tests
