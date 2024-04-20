#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
ROOT_DIR="$(dirname "$(dirname "$DIR")")"

cd "$ROOT_DIR"

bash stages/ci/run-php.sh \
	vendor/nette/tester/src/tester \
		-p phpdbg \
		-c "tests/php.ini" \
		--info

bash stages/ci/run-php.sh \
	vendor/nette/tester/src/tester \
		-p phpdbg \
		-c "tests/php.ini" \
		-o console \
		-j 8 \
		-s \
		--coverage "log/coverage.html" \
		--coverage-src "src" \
		--setup "tests/setup.php" \
		"tests/cases" \
		"tests/packages"

bash tests/cases/acceptance/new-files-covered.sh
