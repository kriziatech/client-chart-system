# Docker Setup for Interior Touch PM

This project is containerized using Docker and Docker Compose. It includes:
- **App Service**: Apache + PHP 8.2 with Laravel dependencies and Node.js for assets.
- **Database**: MySQL 8.0.
- **Tools**: phpMyAdmin for database management.

## Prerequisites
- Docker and Docker Compose installed on your system.

## Getting Started

1. **Build and Start Containers**
   Run the following command in the project root:
   ```bash
   docker compose up -d --build
   ```

2. **Access the Application**
   - **App**: http://localhost:8081
   - **phpMyAdmin**: http://localhost:8080 (Server: `db`, Username: `root`, Password: `secret`)

3. **Database Setup (First Time Only)**
   The database needs to be migrated and seeded. Run:
   ```bash
   docker compose exec app php artisan migrate --seed
   ```

## Development
- The local project directory is mounted into the container, so code changes are reflected immediately.
- To run Artisan commands:
  ```bash
  docker compose exec app php artisan <command>
  ```
- To run NPM commands (if needed):
  ```bash
  docker compose exec app npm <command>
  ```

## Troubleshooting
- **Permissions**: If you encounter permission errors, run:
  ```bash
  docker compose exec app chown -R www-data:www-data storage bootstrap/cache
  ```
- **Database Connection**: The `.env` file is used, but `docker-compose.yml` provides defaults if variables are missing. Default DB password is `secret`.

## Stopping
To stop the containers:
```bash
docker compose down
```
