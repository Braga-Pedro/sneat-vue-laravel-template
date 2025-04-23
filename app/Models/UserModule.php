<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserModule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'admin', 'operator', 'deleted_at'];

    protected $dates = ['created_at', 'deleted_at', 'updated_at'];

    protected $appends = ['amount', 'created_at_format', 'only_one_display'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAmountAttribute()
    {
        $module = 0;

        if ($this->admin)
            $module++;


        if ($this->operator)
            $module++;

        return $module;
    }

    public function getOnlyOneDisplayAttribute()
    {
        $module = 0;

        if ($this->admin)
            return 'admin';


        if ($this->operator)
            return 'operator';
    }

    public function getCreatedAtFormatAttribute()
    {
        return Carbon::make($this->created_at)->setTimezone(auth()->user()->timezone ?? 'UTC')->format('d/m/Y');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? null, 
        function ($query, $name) {
            $query->where('users.name', 'LIKE', "%{$name}%");
        })
        ->when($filters['email'] ?? null, 
        function ($query, $email) {
            $query->where('users.email', 'LIKE', "%{$email}%");
        })
        ->when($filters['phone'] ?? null, 
        function ($query, $phone) {
            $query->where('users.phone', 'LIKE', "%{$phone}%");
        });
    }          
}
