Green ERP
========

This build uses individual PHP dashboard entry points with one shared CSS file and the existing MySQL database schema.

Dashboards
- admin.php
- moderator.php
- accounts.php
- inventory.php
- hr.php
- executive.php

Shared assets
- assets/style.css (unchanged)
- assets/app.js
- api/index.php
- config.php

Login redirects users to their role dashboard. dashboard.php remains as a compatibility router.

Accounts workflow
- Add supplier -> supplier table
- Buy action -> purchase form with supplier preselected
- Purchase transaction methods: Cash, bKash, Bank
- Transaction Details table with Edit/Delete
- Supplier and Purchase tables with Edit/Delete
- Purchase automatically creates/updates its linked expense transaction

The database.sql file is retained; the application does not require a new database name.
