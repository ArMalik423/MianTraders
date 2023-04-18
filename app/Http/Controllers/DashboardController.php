<?php

namespace App\Http\Controllers;

use App\Http\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    /**
     * Constructor injects DashboardService in DashboardController.
     *
     * @param DashboardService $dashboardService
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return void
     */
    public function dashboard(Request $request)
    {
        return $this->dashboardService->getDashboard($request);
    }
}
