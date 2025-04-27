README File for Expense Tracker Application

1. Setup Instructions:
Clone the Repository:

git clone https://github.com/av6432401/expense-tracker.git
cd expense-tracker

Set up the Environment:

Copy .env.example to .env:

Update database connection details in .env:

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expense_tracker
DB_USERNAME=root
DB_PASSWORD=yourpassword

Install Dependencies:
composer install

Create the Database:

CREATE DATABASE expense_tracker;
Run Migrations:

php artisan migrate
Start the Development Server:

php artisan serve
The application will be accessible at http://localhost:8000.

2. Application Features:

User Authentication:
Users can register, log in, and log out.

Expense Management:
Add, edit, and delete expenses, each with a description, amount, category, and date.

Expense Summary:
View the total expenses and a breakdown by category for each day.

Responsive Design:

Built with Bootstrap to ensure the app is mobile-friendly.

Bonus Features:

Dashboard: Visualizes expenses over time using Chart.js.

CSV Export: Allows users to export their expenses to a CSV file.