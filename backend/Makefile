ifneq (,$(wildcard ./.env))
    include .env
    export
endif

.PHONY: all serve format clean

all: serve

# Start PHP development server
serve:
	@echo "Starting PHP server on http://$(PHP_SERVER_HOST):$(PHP_SERVER_PORT)"
	@php -S $(PHP_SERVER_HOST):$(PHP_SERVER_PORT) -t $(PUBLIC_DIR) ./router.php

# Format all code
format:
	@echo "Formatting PHP files..."
	@find . -name '*.php' -exec php -l {} \; > /dev/null
	@echo "PHP files validated (no formatter applied)"

# Clean up temporary files
clean:
	@echo "Cleaning up..."
	@find . -type f -name '*.php~' -delete
	@echo "Done!"
