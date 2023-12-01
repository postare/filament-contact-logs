<?php

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
        'App\Models\Miogest\Property' => [
            'type' => 'Immobile',
            'pluralType' => 'Immobili',
            'label' => 'ID: {id}',
            'urlPrefix' => 'property',
            'titleField' => 'titolo',
            'slugField' => 'slug',
        ],
    ],
];
