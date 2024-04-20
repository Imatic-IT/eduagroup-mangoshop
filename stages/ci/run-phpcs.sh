#!/usr/bin/env bash
set -euo pipefail

bash stages/ci/install-phpcs.sh

bash stages/ci/run-php.sh ./tools/coding-standard/vendor/symplify/easy-coding-standard/bin/ecs check src/ packages/ tests/ apps/ --config coding-standard.neon $@
