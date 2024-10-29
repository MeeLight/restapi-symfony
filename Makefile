.PHONY: Commands

# Install dependencies
deps:
	composer install

# Run the application
dev:
	symfony server:start --no-tls

# Show info of the application
info:
	php bin/console about

# Check security of the application
security:
	symfony check:security
