<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doc extends Model
{
    //
    public $guarded = ['id'];
    protected $table = 'docs';

}
