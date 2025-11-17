<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class OfficeInfo extends Model
{
    protected $table = 'office_info';

    protected $fillable = [
        'practice_name',
        'phone',
        'email',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip',
        'monday_hours',
        'tuesday_hours',
        'wednesday_hours',
        'thursday_hours',
        'friday_hours',
        'saturday_hours',
        'sunday_hours',
        'emergency_message',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('office_info');
        });
    }

    public function getFullAddressAttribute(): string
    {
        $address = $this->address_line1;
        if ($this->address_line2) {
            $address .= ', ' . $this->address_line2;
        }
        $address .= ', ' . $this->city . ', ' . $this->state . ' ' . $this->zip;

        return $address;
    }

    public function getHoursArrayAttribute(): array
    {
        return [
            'Monday' => $this->monday_hours,
            'Tuesday' => $this->tuesday_hours,
            'Wednesday' => $this->wednesday_hours,
            'Thursday' => $this->thursday_hours,
            'Friday' => $this->friday_hours,
            'Saturday' => $this->saturday_hours,
            'Sunday' => $this->sunday_hours,
        ];
    }
}
