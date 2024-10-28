.PHONY: Commands

# Install dependencies
deps:
	cls & composer install

# Run the application
dev:
	cls & symfony server:start --no-tls
