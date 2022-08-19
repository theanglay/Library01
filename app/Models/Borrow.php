<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'borrows';
     protected $primaryKey = 'id';
    // public $timestamps = false;
//    protected $guarded = ['id'];
    protected $fillable = ['book_id','member_id','member_status','borrow_date','return_date','school_id'];
    // protected $hidden = [];
    // protected $dates = [];

    public function MM(){
        return $this->belongsTo(Member::class,'member_id','id');
    }
    public function books(){
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }


}
