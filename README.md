# WSI - MovieTheater API

## Overview

The WSI - MovieTheater API is a robust web application designed to manage various resources related **to** **a** movie theater, including movies, genres, bookings, seats, halls, showtimes, and movie certifications. Built on the Laravel framework, this API provides a seamless experience for both users and administrators.

## Features

-   **User Authentication**: Secure authentication using Laravel Sanctum with Bearer Token support.
-   **Resource Management**: Admin users can create, update, and delete movies, genres, and showtimes.
-   **Booking System**: Users can book seats for showtimes and manage their bookings.
-   **Flexible API Endpoints**: Comprehensive endpoints for managing and retrieving data related to movies, genres, and more.

## Getting Started

### Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/putraprdn/movie-theater-api.git
    ```
2. Navigate to the project directory:
    ```bash
    cd movie-theater-api
    ```
3. Install dependencies:
    ```bash
    composer install
    ```
4. Set up your `.env` file:
    ```bash
    cp .env.example .env
    ```
5. Generate the application key:
    ```bash
    php artisan key:generate
    ```
6. Run migrations:
    ```bash
    php artisan migrate
    ```

### Running the Application

Start the local development server:

```bash
php artisan serve
```

Access the API at `http://localhost:8000/api`.

## API Documentation

The API documentation is available in the Postman collection format. You can import the provided `MovieTheater.postman_collection.json` file into Postman to view all available endpoints and their structures.

### Postman Collection

-   **File**: [MovieTheater.postman_collection.json](MovieTheater.postman_collection.json)
-   **Usage**: Import this collection into Postman to explore the API endpoints, including detailed descriptions, request methods, and example payloads.

## Endpoints Overview

The API manages the following resources:

-   **Movies**: CRUD operations for managing movies.
-   **Genres**: CRUD operations for managing genres.
-   **Bookings**: Operations for managing user bookings.
-   **Seats**: Operations for retrieving seat information.
-   **Halls**: Operations for managing hall information.
-   **Showtimes**: Operations for scheduling and retrieving showtimes.
-   **Movie Certifications**: CRUD operations for managing movie certifications.****

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Acknowledgments

-   Laravel Framework
-   Postman for API documentation
