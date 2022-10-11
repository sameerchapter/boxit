<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingData extends Model
{
    use HasFactory;
    protected $fillable = ['department_id','contact_id','date','booking_id'];
    

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
    
}