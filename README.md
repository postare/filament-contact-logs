# Filament Contact Logs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/postare/filament-contact-logs.svg?style=flat-square)](https://packagist.org/packages/postare/filament-contact-logs)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/postare/filament-contact-logs/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/postare/filament-contact-logs/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/postare/filament-contact-logs/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/postare/filament-contact-logs/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/postare/filament-contact-logs.svg?style=flat-square)](https://packagist.org/packages/postare/filament-contact-logs)

Filament Contact Logs is a Filament 3.x plugin that performs a clear and critical function: it records in a log every
time a
contact is sent. Regardless of the contact's final destination, the plugin ensures that each submission is tracked in
detail. This allows operators to have a complete history of all communications received, providing a clear and organized
picture of interactions and feedback. Thus, its main function is to ensure total traceability of contacts, facilitating
management and follow-up by operators.

## Installation

Install the package via composer:

```bash
composer require postare/filament-contact-logs
```

Publish and run the migrations:

```bash
php artisan vendor:publish --tag="filament-contact-logs-migrations"
php artisan migrate
```

Publish the config file:

```bash
php artisan vendor:publish --tag="filament-contact-logs-config"
```

Published config file contents:

```php
return [
    /*
     * When the clean-command is executed, all recording activities older than
     * the number of days specified here will be deleted.
     */
    'delete_records_older_than_days' => 365,

    /*
     * Model to use to log activity.
     */
    'contact_log_model' => \Postare\FilamentContactLogs\Models\ContactLog::class,

    /*
     * List of Models associated with contact messages, it is not mandatory
     * to specify them, it is for obtaining a more user-friendly table.
     */
    'mappings' => [
        //        EXAMPLE:
        //        'App\Models\Property' => [
        //            'type' => 'Property',
        //            'pluralType' => 'Properties',
        //            'label' => 'ID: {id}',
        //            'titleField' => 'name',
        //            'route' => 'property',
        //            'record_identifier' => 'slug',
        //        ],
    ],
];
```

## Usage

### Integrating with Filament Panel

```php
// app\Providers\Filament\AdminPanelProvider.php
$panel
    ...
    ->plugins([
        ...
        \Postare\FilamentContactLogs\FilamentContactLogsPlugin::make(),
    ]);
```

### Associating Models with Contact Logs

```php
// Use HasContactLogs trait in your model
use Postare\FilamentContactLogs\Traits\HasContactLogs;
```

### Examples

Add a contact log to a model:

```php
Property::find(1)->addContactLog([
    'content' => 'A beautiful message',
    'subject' => 'A subject',
    'sender_id' => auth()?->id(),       // optional
    'sender_email' => 'user@email.com',
    'sender_name' => 'John Doe',
    'sender_phone' => '1234567890',     // optional
]);
```

Add a contact log without a model:

```php
contactLog([
    'content' => 'A beautiful message',
    'subject' => 'A subject',
    'sender_id' => auth()?->id(),       // optional
    'sender_email' => 'user@email.com',
    'sender_name' => 'John Doe',
    'sender_phone' => '1234567890',     // optional
]);
```

### Auto-Deleting Logs

Automatically delete logs based on config settings:

```php
// Add the following to your app\Console\Kernel.php
protected function schedule(Schedule $schedule): void
{
    $schedule->command('contact-logs:clean')->daily();
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Credits

- [Francesco Apruzzese](https://github.com/inerba)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
