<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\WebsiteConnection;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'website',
        'address',
        'description',
        'logo',
        'status',
    ];

    // Organization owner and staff members
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot(['organization_role', 'is_active'])
            ->withTimestamps();
    }

    // Only active organization members
    public function activeUsers(): BelongsToMany
    {
        return $this->users()->wherePivot('is_active', true);
    }

    // Organization products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function websiteConnection()
{
    return $this->hasOne(WebsiteConnection::class);
}

    // Organization services
public function services()
{
    return $this->hasMany(Service::class);
}

// Organization orders
public function orders()
{
    return $this->hasMany(Order::class);
}

// Organization appointments
public function appointments()
{
    return $this->hasMany(Appointment::class);
}

// Organization conversations
public function conversations()
{
    return $this->hasMany(Conversation::class);
}

}
