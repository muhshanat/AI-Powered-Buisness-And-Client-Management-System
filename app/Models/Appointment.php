<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'service_id',
        'appointment_number',
        'service_name',
        'service_price',
        'customer_name',
        'customer_email',
        'customer_phone',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'service_price' => 'decimal:2',
        ];
    }

    // Appointment belongs to an organization
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // Appointment belongs to a service
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
