<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class MasterDataController extends Controller
{
    public function index()
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        return view('master_data.index', compact('breadcrumbs'));
    }
}
