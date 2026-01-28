<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'status_text'
    ];
    public function getStatusTextAttribute()
    {
        $statuses = [
            1 => 'новая',
            2 => 'в работе',
            3 => 'исполнена',
            4 => 'отклонена',
            5 => 'отменена'
        ];
        
        return $statuses[$this->status] ?? 'неизвестный статус';
    }
}
