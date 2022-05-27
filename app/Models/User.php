<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // protected $table        = 'users';

    protected $fillable = [
        'first_name','last_name','email','password'
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */

    public function pages()
    {
        return $this->hasMany(\App\Models\Page::class);
    }
}
