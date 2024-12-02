Bill Payment API with Wallet System
Objective
The objective of this project is to build a RESTful API for a bill payment system focusing on eletricity purchase and a wallet system. The implementation will evaluate concurrency handling, wallet functionality, transaction management, and adherence to best practices in Laravel development.

Features
Functional Requirements
1. Wallet System
Users can:
Check wallet balance.
Fund their wallet.
Deduct wallet balance for transactions.
Transactions are accurate and concurrency-safe under simultaneous requests.
2. Bill Payment
API endpoint to purchase airtime (or another service, e.g., data or electricity).
Simulate service by logging successful transactions (no external integrations).
3. Transaction History
Users can view their transaction history:
Wallet funding transactions.
eletricity purchase transactions.

Deliverables
register
post /register
login
post /login

1. API Endpoints
Wallet
GET /wallet/balance
Fetch the user's wallet balance.
POST /wallet/fund
Fund the wallet by specifying an amount.
POST /wallet/deduct
Deduct the wallet by specifying an amount
Airtime Purchase
POST /purchase/eletricity
Purchase eletricity by deducting the wallet balance and logging the transaction.
Transactions
GET /transactions
Retrieve the user's transaction history (wallet funding and airtime purchases).
3. Database Schema
Tables:
users:
Stores user information (e.g., id, name, email, password).
wallets:
Tracks wallet balances (id, user_id, balance).
transactions:
Records all wallet and airtime transactions (id, user_id, type, amount, description, created_at).
4. Codebase
Built with Laravel.
Includes:
Wallet logic: Handles balance updates and concurrency.
Authentication: Ensures secure access to API endpoints.
Validation: Validates input and prevents invalid operations.
Testing: Includes PHPUnit/Pest tests for core functionalities.
Error handling: Provides meaningful error messages.

Setup Instructions
Clone the Repository

git clone https://github.com/chiomalovet/Bill_Payment_API_with_Wallet_System.git
cd Bill_Payment_API_with_Wallet_System
Install Dependencies

composer install
Set Up Environment

Copy the .env.example file to .env:
cp .env.example .env

Update the .env file with your database and Paystack credentials.
Run Migrations

php artisan migrate

Run the Application
php artisan serve
