#
# Targets:
#  - test	run unit tests

# Binary
PHPUNIT = phpunit

# Path
ROOT = .
PROJECT_TEST_PATH = $(ROOT)/tests

# Run unit tests
test:
	@cd $(PROJECT_TEST_PATH) && $(PHPUNIT) --coverage-html coverage
