## SEGA Host Rental Manager

SEGA Host is a server rental management platform that streamlines provisioning, monitoring, and return workflows. Admins can oversee inventory and approvals, while customers track their own rentals, configurations, and settlement history.

### Key Features
- Role-based dashboards for administrators and customers with tailored metrics.
- Streamlined rental status flow: `pending`, `active`, `completed`, `overdue` with automatic overdue detection.
- Admin return queue that tracks requests using `previous_status` to handle pending returns safely.
- Detailed rental show page for users with configuration toggle, penalty and refund breakdown, and settlement history.
- Configurable server profiles combining base specs and custom fields for each unit.

### Core Roles
- **Administrator**: Manages server inventory, approves returns, and monitors system metrics such as total units, total rentals, pending returns, and overdue rentals.
- **Customer**: Reviews active or historical rentals, inspects server configuration, and tracks costs, penalties, or refunds before closing a rental.

### Local Development
1. Install dependencies: `composer install` and `npm install`
2. Copy `.env.example` to `.env` and update database credentials
3. Generate app key: `php artisan key:generate`
4. Run migrations and seeders: `php artisan migrate --seed`
5. Build assets: `npm run dev` or `npm run build`
6. Start the app: `php artisan serve`

### Testing
- Run full suite: `php artisan test`
- Run feature tests only: `php artisan test --testsuite=Feature`

### Project Structure Highlights
- `app/Http/Controllers/Admin` contains dashboard logic and return-queue handling
- `resources/views/admin` and `resources/views/user` hold interface templates for each role
- `database/migrations` defines rental, configuration, and penalty schema
- `database/seeders` seeds baseline users, units, and configuration profiles

### Contributions & Licensing
This repository is intended for internal use. Please coordinate with the project owners before submitting pull requests or sharing forks.
