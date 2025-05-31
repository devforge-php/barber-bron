<?php

namespace App\Http\Controllers\Barber\Commmets;

use App\Http\Controllers\Controller;
use App\Service\CommetService;
use Illuminate\Http\Request;

class CommetController extends Controller
{
    public function __construct(protected CommetService $commetService) {}

    public function index()
    {
        return $this->commetService->getAll();
    }

    public function show(string $id)
    {
        return $this->commetService->getById($id);
    }

    public function destroy(string $id)
    {
        return $this->commetService->delete($id);
    }
}
