<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $primaryKey = "sub_id";
    protected $fillable = ['web_id','u_id'];

    public function subsscriber()
    {
        return $this->belongsTo('App\Models\User','u_id');
    }
}
