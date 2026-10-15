# TechXP Event Registration & Attendee Management System

## Overview

A web-based event registration and attendee management platform built using CodeIgniter 4, PHP, and MySQL.

## Features

- Attendee registration with input validation
- Password hashing and login authentication
- Session-based authentication and logout
- Protected event page
- Admin-only dashboard
- Attendee search, country filtering, and pagination
- Individual attendee details
- REST API endpoints for attendee listing, retrieval, updating, and deletion
- External API integration for random activity suggestions
- Mock chat interface with simulated replies
- Basic security checks and access controla

## Tech Stack

- Backend: PHP, CodeIgniter 4
- Database: MySQL
- Frontend: HTML, CSS, JavaScript
- Local development: XAMPP

## Setup Instructions

1. Install PHP, Composer, and MySQL, or use XAMPP for the local PHP/MySQL environment.
2. Place the project in your local development directory.
3. Install project dependencies:

   ```bash
   composer install
   ```

4. Create a MySQL database named `techxp_assessment`.
5. Configure database credentials in `app/Config/Database.php`, or use the project's existing environment configuration.
6. Configure the application's base URL in `app/Config/App.php` or the relevant environment settings.
7. Start Apache and MySQL.
8. From the project directory, run:

   ```bash
   php spark serve --port 8080
   ```

9. Open `http://localhost:8080` in your browser.

## Main Pages

- `/register` — Attendee registration
- `/login` — Login
- `/event` — Protected event page
- `/admin` — Admin dashboard
- `/logout` — Logout

## API Endpoints

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/register` | Register an attendee |
| POST | `/api/login` | Authenticate an attendee |
| GET | `/api/profile` | Get the logged-in attendee's profile |
| GET | `/api/attendees` | List attendees (admin only) |
| GET | `/api/attendees/{id}` | Retrieve an attendee (admin only) |
| PUT | `/api/attendees/{id}` | Update an attendee (admin only) |
| DELETE | `/api/attendees/{id}` | Delete an attendee (admin only) |
| GET | `/api/registrations` | List registrations |
| GET | `/api/registrations/{id}` | Retrieve a specific registration |
| POST | `/api/registrations` | Register for the event |
| POST | `/api/chat` | Send a message to the mock chat API |
| GET | `/api/external-activity` | Fetch an external activity suggestion |


## Chat Interface

The event page includes a mock chat interface. Replies are simulated in the frontend; it is not a real-time messaging service.

## Security

- Passwords are stored as password hashes.
- Admin API endpoints require an authenticated admin session.
- Attendee responses exclude password hashes.
- Input validation and error handling are implemented in relevant flows.

## Notes

- Configure your own local database credentials before running the project.
- The external activity feature requires internet connectivity.
- Use disposable test records when testing attendee deletion.
- Do not commit real credentials or secrets to version control.
