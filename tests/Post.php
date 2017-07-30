<?php

namespace PawelMysior\Publishable\Tests;

use Illuminate\Database\Eloquent\Model;
use PawelMysior\Publishable\Publishable;

class Post extends Model
{
    use Publishable;
    
    protected $guarded = [];
}
