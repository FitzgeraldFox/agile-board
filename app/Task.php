<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['title', 'description', 'estimation', 'sprint_id'];
    public $timestamps = false;

    public function sprint()
    {
        $this->belongsTo('App\Sprint');
    }
}
