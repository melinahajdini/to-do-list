<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDo extends Model
{

    protected $fillable = ['title', 'description', 'deletion_date', 'picture', 'user_id'];
    protected $table = 'todo-list';

}
