<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'organizer_id',
        'category_id',
        'title',
        'description',
        'date',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'poster',
        'registration_url',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
