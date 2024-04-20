#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

COVERAGE_FILE="$DIR/../../../log/coverage.html"
COVERAGE="$(grep -C1 'div class="bar"' "$COVERAGE_FILE" | awk -F'[<> ]' 'BEGIN{RS="--"} {print $32"\t"$6}' | sed 's/\\/\//g' | sort | uniq)"
export COVERAGE

MERGE_BASE="$(git merge-base refs/remotes/origin/master HEAD)"
NEW_SOURCE_FILES="$(git diff "$MERGE_BASE" --name-only --diff-filter=A -- app | grep '.php$' | grep Styleguide -v || echo '')"

function get-coverage() {
	FILE="$(echo "$1" | sed 's|^app/||')"
	echo "$COVERAGE" | egrep "^$FILE"$'\t' | awk -F'\t' '{print $2}' || echo 'n/a'
}

function is-skipped() {
	FILE="$1"
	cat "$FILE" | grep --silent --count '@ignoreInCoverageTest'
}

FAILED=0
for NEW_FILE in $NEW_SOURCE_FILES; do
	PERCENT="$(get-coverage "$NEW_FILE")"
	if is-skipped "$NEW_FILE"; then
		echo "$NEW_FILE skipped"
		continue;
	fi;
	if [ $PERCENT -lt 70 ]; then
		if [ $FAILED -eq 0 ]; then
			echo "Detected new files without enough coverage:"
		fi
		echo "  $NEW_FILE ($PERCENT%)"
		FAILED=1
	fi
done

exit "$FAILED"
