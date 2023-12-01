<?php

namespace Postare\FilamentContactLogs\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Postare\FilamentContactLogs\Models\ContactLog;

/**
 * @method morphMany(string $class, string $string)
 */
trait HasContactLogs
{
    /**
     * Get all messages associated with the model.
     */
    public function contactLogs(): MorphMany
    {
        return $this->morphMany(ContactLog::class, 'contactable');
    }

    /**
     * Add a new message to the model.
     *
     * @param  array  $attributes The message attributes to be added.
     * @return ContactLog The created message.
     *
     * @throws ValidationException
     */
    public function addContactLog(array $attributes): ContactLog
    {
        // Validation rules
        $validator = Validator::make($attributes, [
            'content' => 'required',
            'subject' => 'required',
            'sender_id' => 'nullable|exists:users,id',
            'sender_email' => 'required_without:sender_id|email',
            'sender_name' => 'required',
            'sender_phone' => 'nullable',
        ]);

        // Throw an exception if validation fails
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Create and return the message
        return $this->contactLogs()->create($validator->validated());
    }
}
