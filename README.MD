
Bonza Quotes Plugin
===================

A custom WordPress plugin that handles service quote requests through a frontend form and allows admin management of submitted quotes. Built with OOP principles, security best practices, and extensibility in mind.

Features
--------
- Frontend quote submission form via `[bonza_quote_form]` shortcode
- Stores submissions in a custom database table (`wp_bonza_quotes`)
- Admin panel to view and update quote statuses (Approved / Rejected)
- Sends email notification to the admin when a new quote is submitted
- Uses WordPress actions and filters for extensibility
- Unit tested with PHPUnit

Installation
------------
1. Clone or download the plugin into your `wp-content/plugins/` directory
2. Run:

       composer install

3. Activate the plugin in WordPress admin
4. Add the shortcode `[bonza_quote_form]` to any page or post

Admin Panel
-----------
After activation, go to:
Dashboard > Bonza Quotes

View submitted quotes, and update their status via dropdown.

Email Notifications
-------------------
When a user submits a quote, the site admin will receive an email with the details. Uses `wp_mail()`.

To make sure emails send properly, you can use a plugin like WP Mail SMTP.

Extensibility
-------------

**Action Hook**

    do_action( 'bonza_quote_submitted', $quote_id, $data );

Fires after a quote is inserted.

**Filter Hooks**

    apply_filters( 'bonza_quote_email_subject', 'New Quote Submitted', $data );
    apply_filters( 'bonza_quote_email_message', $default_message, $data );

Customize the email subject or body before it's sent.

Testing
-------------

This plugin includes automated tests using PHPUnit to verify the integrity of core logic.

### How to Run the Tests

1. **Make sure dependencies are installed** (from the plugin root):

    ```bash
    composer install
    ```

2. **Run PHPUnit:**

    ```bash
    vendor/bin/phpunit
    ```

This will run all test classes inside the `tests/` directory. The provided `QuoteRepositoryTest` ensures essential methods like `install()` and `insert()` are available and working.

> Note: This test setup is isolated where it simply verifies class structure and behavior using PHPUnit directly.

Plugin Structure
----------------

    bonza-quotes/
    ├── src/
    │   ├── Plugin.php
    │   ├── Database/
    │   │   └── QuoteRepository.php
    │   ├── Frontend/
    │   │   └── QuoteForm.php
    │   └── Admin/
    │       └── QuotesAdmin.php
    ├── tests/
    │   ├── QuoteRepositoryTest.php
    │   └── bootstrap.php
    ├── vendor/ (Composer dependencies)
    ├── phpunit.xml
    ├── composer.json
    └── README.md

Security Notes
--------------
- All user input is sanitized using WordPress functions (`sanitize_text_field`, `sanitize_email`, etc.)
- CSRF protection via `wp_nonce_field()`
- Database queries use `$wpdb` with safe methods (`insert`, `update`, `get_results`)
