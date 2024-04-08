<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Bug;
use Illuminate\Http\Request;

class BugController extends Controller
{
    public function index()
    {
        $bugs = Bug::latest()->paginate(30);
        return view('panel.bugs.index', compact('bugs'));
    }
}
