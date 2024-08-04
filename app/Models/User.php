<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    // Define static array for gender
    public static $genders = [
        1 => 'Male',
        2 => 'Female',
        3 => 'Other'
    ];

    // Define constants for status
    const STATUS_ACTIVE = 1;
    const STATUS_IN_ACTIVE = 2;

    // Define static array for status
    public static $statuses = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_IN_ACTIVE => 'In-active'
    ];

    public static $ageOperator = [
        'eq' => 'Equals To',
        'gt' => 'Greater Then',
        'lt' => 'Less Then'
    ];

    // Define constants for deisgnations/Roles
    const DESIGNATION_PRESIDENT = 2;
    const DESIGNATION_VICE_PRESIDENT = 3;
    const DESIGNATION_FINANCE_SECRETARY = 4;
    const DESIGNATION_SECRETARY = 5;
    const DESIGNATION_JOINT_SECRETARY = 6;
    const DESIGNATION_EXECUTIVE_MEMBER = 7;
    const DESIGNATION_MANAGER = 8;
    const DESIGNATION_LIBRARIAN = 9;
    const DESIGNATION_LAWYER = 10;
    const DESIGNATION_VENDOR = 11;

    // Define static array for designation
    // Note: the keys are exists same in DB
    public static $designationRoles = [
        self::DESIGNATION_PRESIDENT => 'President',
        self::DESIGNATION_VICE_PRESIDENT => 'Vice President',
        self::DESIGNATION_FINANCE_SECRETARY => 'Finance Secretary',
        self::DESIGNATION_SECRETARY => 'Secretary',
        self::DESIGNATION_JOINT_SECRETARY => 'Joint Secretary',
        self::DESIGNATION_EXECUTIVE_MEMBER => 'Executive Member',
        self::DESIGNATION_MANAGER => 'Manager',
        self::DESIGNATION_LIBRARIAN => 'Librarian',
        self::DESIGNATION_LAWYER => 'Lawyer',
        self::DESIGNATION_VENDOR => 'Vendor',
    ];

    const PENDING_REQUEST = "Pending";
    const APPROVED_REQUEST = "Approved";
    const REJECTED_REQUEST = "Rejected";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "first_name",
        "middle_name",
        "last_name",
        "email",
        "father_first_name",
        "father_last_name",
        "dob",
        "gender",
        "mobile1",
        "mobile2",
        "aadhaar_no",
        "licence_no",
        "picture",
        "designation",
        "degrees",
        "address",
        "status",
        "chamber_number",
        "deleted_by",
        "is_deceased",
        "is_physically_disabled",
        "other_details",
        "account_approved",
        "account_modified",
        "password"
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

    public function getFullNameAttribute()
    {
        $middleName = $this->middle_name ? ' ' . $this->middle_name : '';
        $lastName = $this->last_name ? ' ' . $this->last_name : '';
        return "{$this->first_name}{$middleName} {$lastName}";
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['dob'])->age;
    }

    public function getFullFatherNameAttribute()
    {
        $father_last_name = $this->father_last_name ? ' ' . $this->father_last_name : '';
        return "{$this->father_first_name}{$father_last_name}";
    }

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

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    // Scope for active users only
    public function scopeStatusActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function address_proof()
    {
        return $this->hasMany(AddressProof::class, 'user_id', 'id');
    }

    public function other_documents()
    {
        return $this->hasMany(OtherDocument::class, 'user_id', 'id');
    }

    public function degree_images()
    {
        return $this->hasMany(DegreeImage::class, 'user_id', 'id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function issuedBooks()
    {
        return $this->hasMany(IssuedBook::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function updateRequest()
    {
        return $this->hasMany(UserUpdateRequest::class, 'user_id', 'id');
    }

    public function latestUpdateRequest()
    {
        return $this->hasOne(UserUpdateRequest::class)->latest();
    }

    public function vendorInfo()
    {
        return $this->hasOne(Vendor::class, 'user_id', 'id');
    }

    public static function getAgeOperator($key)
    {
        switch ($key) {
            case 'eq':
                return '=';
            case 'gt':
                return '>';
            case 'lt':
                return '<';
            default:
                return '=';
        }
    }

    public function scopeAge(Builder $query, $age, $operatorKey = 'eq')
    {
        $operator = User::getAgeOperator($operatorKey);
        return $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) $operator ?", [$age]);
    }

    public function families()
    {
        return $this->hasMany(Family::class);
    }
}
