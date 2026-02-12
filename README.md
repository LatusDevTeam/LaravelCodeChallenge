# Latus - Programming Jokes Application

A full-stack application featuring a Laravel backend API and Vue.js frontend that displays programming jokes.

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.x)
- **Frontend**: Vue 3 + TypeScript + Vite + Tailwind CSS
- **Database**: MySQL 8
- **Containerization**: Docker & Docker Compose

## Prerequisites

- [Docker](https://docs.docker.com/get-docker/) (v20.10+)
- [Docker Compose](https://docs.docker.com/compose/install/) (v2.0+)

## Project Structure

```
latus/
├── backend/          # Laravel API
├── frontend/         # Vue.js SPA
├── compose.yml       # Docker Compose configuration
└── README            # This file
```

## Quick Start

### 1. Clone and Navigate

```bash
cd latus
```

### 2. Environment Setup

Create the backend environment file:

```bash
cp backend/.env.example backend/.env
```

### 3. Start the Application

```bash
docker compose up -d --build
```

This will start:
- **Frontend** at [http://localhost:5173](http://localhost:5173)
- **Backend API** at [http://localhost:8080](http://localhost:8080)
- **MySQL Database** on port 3306

### 4. Generate Laravel App Key

```bash
docker compose exec php php artisan key:generate
```

### 5. Run Database Migrations (if needed)

```bash
docker compose exec php php artisan migrate
```

## Running Tests

### Backend Tests (PHPUnit)

```bash
docker compose exec php php artisan test
```

Or run specific test files:

```bash
docker compose exec php php artisan test --filter=JokeServiceTest
docker compose exec php php artisan test --filter=JokeControllerTest
```

### Frontend Tests (Vitest)

```bash
docker compose exec frontend npm run test:unit -- --run
```

Or run in watch mode:

```bash
docker compose exec frontend npm run test:unit
```

## API Endpoints

| Method | Endpoint     | Description                  |
|--------|--------------|------------------------------|
| GET    | `/api/jokes` | Fetch programming jokes      |

### Query Parameters

- `filters[limit]` - Number of jokes to return (default: 10)

### Example Request

```bash
curl "http://localhost:8080/api/jokes?filters[limit]=3"
```

## Development

### Viewing Logs

```bash
# All services
docker compose logs -f

# Specific service
docker compose logs -f frontend
docker compose logs -f php
docker compose logs -f nginx
```

### Stopping the Application

```bash
docker compose down
```

### Rebuilding Containers

```bash
docker compose down
docker compose up -d --build
```

### Accessing Containers

```bash
# PHP/Laravel container
docker compose exec php bash

# Frontend container
docker compose exec frontend sh

# Database container
docker compose exec database bash
```

## License

This project is open-sourced software.
