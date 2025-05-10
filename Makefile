ifneq (,$(wildcard ./.env))
    include .env
    export
endif

.PHONY: all serve format lint lint-php lint-js lint-css clean

all: serve

# Start PHP development server
serve:
	@echo "Starting PHP server on http://$(PHP_SERVER_HOST):$(PHP_SERVER_PORT)"
	@php -S $(PHP_SERVER_HOST):$(PHP_SERVER_PORT) -t $(PUBLIC_DIR) ./router.php

# Format all code
format: format-php format-js format-css

format-php:
	@echo "Formatting PHP files..."
	@find . -name '*.php' -exec php -l {} \; > /dev/null
	@echo "PHP files validated (no formatter applied)"

format-js:
	@if command -v prettier >/dev/null 2>&1; then \
		echo "Formatting JS files with Prettier..."; \
		prettier --write $(JS_FILES); \
	else \
		echo "Prettier not found. Install with: npm install -g prettier"; \
	fi

format-css:
	@if command -v prettier >/dev/null 2>&1; then \
		echo "Formatting CSS files with Prettier..."; \
		prettier --write $(CSS_FILES); \
	else \
		echo "Prettier not found. Install with: npm install -g prettier"; \
	fi

# Clean up temporary files
clean:
	@echo "Cleaning up..."
	@find . -type f -name '*.php~' -delete
	@find . -type f -name '*.js~' -delete
	@find . -type f -name '*.css~' -delete
	@echo "Done!"
