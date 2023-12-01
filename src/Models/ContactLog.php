<?php

namespace Postare\FilamentContactLogs\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ContactLog extends Model
{
    protected $table = 'contact_logs';

    protected $fillable = [
        'content',
        'subject',
        'sender_id',
        'sender_email',
        'sender_name',
        'sender_phone',
        'contactable_id',
        'contactable_type',
    ];

    /**
     * Ottieni l'entità associata al messaggio.
     */
    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Utente che ha inviato il messaggio.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Metodo per creare un messaggio di contatto generale
    public static function add(array $attributes)
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

        return self::create($validator->validated());
    }
}
