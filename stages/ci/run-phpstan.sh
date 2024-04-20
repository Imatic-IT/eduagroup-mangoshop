#!/usr/bin/env bash
set -euo pipefail

bash stages/ci/run-php.sh \
	vendor/phpstan/phpstan-shim/phpstan.phar analyse \
		--no-progress \
		--level 7 \
		--configuration phpstan.neon \
		src packages apps
