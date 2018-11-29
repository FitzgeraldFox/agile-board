<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sprint extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['id'];
    public $timestamps = false;

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}
