<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Auth;

trait AuthUser
{
    protected $authUserData = null;

    public function authUserToken($user)
    {
        return intval($user->id) > 0 ? $user->createToken($this->authTokenKey())->plainTextToken : null;
    }

    public function authTokenKey()
    {
        return "kodestudio-token";
    }

    public function getAuthUser()
    {
        return $this->authUserData['auth_user'] = (isset($this->authUserData['auth_user']) && $this->authUserData['auth_user'] != null) ? $this->authUserData['auth_user'] : (Auth::check() ? Auth::user() : null);
    }

    public function getAuthUserId()
    {
        $user = $this->getAuthUser();
        return $user->id ?? 0;
    }

    public function isUserCheckin()
    {
        $user = $this->getAuthUser();
        $userLastCheckinRecord = $user ? $user->lastCheckin() : null;
        return !is_null($userLastCheckinRecord) && is_null($userLastCheckinRecord->checkout);
    }

    public function userLastCheckinTime()
    {
        $userLastCheckinRecord =$this->userLastCheckinDetails();
        return (!is_null($userLastCheckinRecord) && !is_null($userLastCheckinRecord->checkin)) ? $userLastCheckinRecord->checkin : null;
    }
    public function userLastCheckinDetails()
    {
        $user = $this->getAuthUser();
        $userLastCheckinRecord = $user ? $user->lastCheckin() : null;
        return $userLastCheckinRecord;
    }

    public function userRoles()
    {
        $user = $this->getAuthUser();
        $userRoles = $user ? $user->roles : null;
        return $userRoles ? (array_column($userRoles->toArray(), 'id')) : [];
    }

    public function hasRoleAccess($role = '')
    {
        $hasAccess = false;
        if ($role != '') {
            $user = $this->getAuthUser();
            if ($user) {
                $userRoles = $this->userRoles();
                $hasAccess = (in_array($role, $userRoles)) ? true : false;
            }
            return $hasAccess;
        }
    }
}
