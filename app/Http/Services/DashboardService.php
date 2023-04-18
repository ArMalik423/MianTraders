<?php

namespace App\Http\Services;

use App\Http\Enums\RoleUser;
use Illuminate\Http\Request;
use App\Http\Services\BaseService\BaseService;
use Carbon\Carbon;

class DashboardService extends BaseService
{
    /**
     * This method is used to assign dashboard according to role.
     *
     * @param Request $request
     * @return void
     */

     public function getDashboard(Request $request)
     {
         $user = $this->getAuthUser();
         if ($user) {
             $userRoles = $this->userRoles();
             if (in_array(RoleUser::SuperAdmin, $userRoles)) {
                 return $this->adminDashboard($request);
             } elseif (in_array(RoleUser::Viewer, $userRoles)) {
                 return $this->viewDashboard($request);
             }
         }
     }

      /**
     * Return Admin Dashboard To the Admin User.
     *
     * @return void
     */
    public function adminDashboard(Request $request)
    {
        return view('admin.index');
    }
    public function viewDashboard(Request $request)
    {
        return view('viewer.index');
    }
}