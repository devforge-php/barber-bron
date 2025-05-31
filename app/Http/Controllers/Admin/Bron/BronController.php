<?php

namespace App\Http\Controllers\Admin\Bron;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminBronRequest;
use App\Http\Resources\BronResource;
use App\Service\AdminBronService;
use Illuminate\Http\Request;

class BronController extends Controller
{
    public $adminBronService;

    public function __construct(AdminBronService $adminBronService)
    {
        $this->adminBronService = $adminBronService;
    }
    public function post(AdminBronRequest $request)
    {
        // `validate()` chaqirishga hojat yo'q, faqat `validated()` ni uzatamiz
        $bron = $this->adminBronService->adminBronPost($request->validated()); 
        return response()->json(new BronResource($bron));
    }
    
    
}
