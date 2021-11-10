<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class Posts extends Model
{
	protected $table = 'posts';

	protected $fillable = [
        'users_id',
        'title',
        'description',
        'thumbnail',
        'status',
    ];


    // boot() function used to insert logged users_id at 'created_by' & 'updated_by'
    public static function boot(){
        parent::boot();
        static::creating(function($query){
            if(Auth::check()){
                $query->created_by = @\Auth::user()->id;
                $query->users_id = @\Auth::user()->id;
            }
        });
        static::updating(function($query){
            if(Auth::check()){
                $query->updated_by = @\Auth::user()->id;
                $query->users_id = @\Auth::user()->id;
            }
        });
    }
}
