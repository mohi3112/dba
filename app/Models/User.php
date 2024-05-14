<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Define static array for gender
    public static $genders = [
        1 => 'Male',
        2 => 'Female',
        3 => 'Other'
    ];

    // Define static array for designation
    public static $designations = [
        1 => 'Lawyer/Attorney',
        2 => 'Legal Secretary',
        3 => 'Paralegal',
        4 => 'Legal Assistant',
        5 => 'Legal Clerk',
        6 => 'Legal Intern',
        7 => 'Legal Researcher',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'father_first_name',
        'father_last_name',
        'gender',
        'mobile1',
        'mobile2',
        'designation',
        'degrees',
        'address',
        'chamber_number',
        'floor_number',
        'building',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if the user has any of the roles passed in parameter
     *
     * @param array $roles The roles to check
     *
     * @return bool
     */
    public function hasAnyRole(array $roles)
    {
        /**
         * Check if the user has at least one of the roles passed in parameter
         *
         * @return null|Model
         */
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }
}
