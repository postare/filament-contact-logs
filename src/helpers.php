<?php

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Postare\FilamentContactLogs\Models\ContactLog;

if (! function_exists('contactLog')) {
    function contactLog(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'content' => 'required',
            'subject' => 'required',
            'sender_id' => 'nullable|exists:users,id',
            'sender_email' => 'required_without:sender_id|email',
            'sender_name' => 'required',
            'sender_phone' => 'nullable',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return ContactLog::create($validator->validated());
    }
}
