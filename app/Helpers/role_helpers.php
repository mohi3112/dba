<?php

use App\Models\User;

if (!function_exists('getUserRoles')) {
    function getUserRoles()
    {
        $user = auth()->user();
        $roleId = $user->roles()->pluck('role_user.role_id')->first();

        $president = $vice_president = $finance_secretary = $secretary = $joint_secretary = $executive_member = $manager = $librarian = $lawyer = $vendor = 0;

        switch ($roleId) {
            case User::DESIGNATION_PRESIDENT:
                $president = 1;
                break;
            case User::DESIGNATION_VICE_PRESIDENT:
                $vice_president = 1;
                break;
            case User::DESIGNATION_FINANCE_SECRETARY:
                $finance_secretary = 1;
                break;
            case User::DESIGNATION_SECRETARY:
                $secretary = 1;
                break;
            case User::DESIGNATION_JOINT_SECRETARY:
                $joint_secretary = 1;
                break;
            case User::DESIGNATION_EXECUTIVE_MEMBER:
                $executive_member = 1;
                break;
            case User::DESIGNATION_MANAGER:
                $manager = 1;
                break;
            case User::DESIGNATION_LIBRARIAN:
                $librarian = 1;
                break;
            case User::DESIGNATION_LAWYER:
                $lawyer = 1;
                break;
            case User::DESIGNATION_VENDOR:
                $vendor = 1;
                break;
            default:
                $lawyer = 1;
                break;
        }

        return compact('president', 'vice_president', 'finance_secretary', 'secretary', 'joint_secretary', 'executive_member', 'manager', 'librarian', 'lawyer', 'vendor');
    }
}
