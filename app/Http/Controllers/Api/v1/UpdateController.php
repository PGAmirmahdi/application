<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Update;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function getUpdate()
    {
        return Update::orderBy('version', 'desc')->first();
    }
}
