# Restaurant Reservations Dashboard

This is an admin dashboard for managing a Restaurant Reservation system built with Laravel. The system allows hotel guests to make restaurant reservations within their hotel.

## Features

- Manage hotels and their information
- Add, edit and delete restaurants with capacity information
- Manage meal types (breakfast, lunch, dinner, etc.) with translations
- View and manage reservations with filtering by date, hotel, restaurant, and status
- Generate reports on reservation metrics
- Handle special requests and dietary preferences

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd Restaurant-Reservations-Dashboard
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Create your environment file:
   ```bash
   cp .env.example .env
   ```

4. Update your .env file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=dineease
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Generate an application key:
   ```bash
   php artisan key:generate
   ```

6. Set up the dineease database:
   - Make sure you have created a database named 'dineease'
   - Import the provided SQL file into your database

7. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

1. Access the admin dashboard at `http://localhost:8000/admin`
2. Log in with admin credentials (from the admin_users table)
3. Navigate through the dashboard to manage hotels, restaurants, meal types, and reservations

## Dashboard Sections

- **Dashboard**: View summary statistics and recent reservations
- **Hotels**: Manage hotel information
- **Restaurants**: Add and configure restaurants within hotels
- **Meal Types**: Set up meal types with multilingual support
- **Reservations**: View and manage all reservations
- **Reports**: Generate analytics on reservation data

## Design

The dashboard uses a Material Design-inspired interface with a blue/purple color scheme (#4A4EB2, #4E89FF) to match the existing web application.

## Database Structure

The database includes the following main tables:
- Hotels (with active status)
- Restaurants (linked to hotels, with capacity information)
- Meal Types (breakfast, lunch, dinner, etc. with translations)
- Reservations (containing guest details, dates, times)
- Guests (including room numbers and number of guests)

And additional supporting tables for translations, taxes, and other features.

## License

This project is licensed under the [MIT License](LICENSE).
