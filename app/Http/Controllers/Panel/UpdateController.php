<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRequest;
use App\Http\Requests\UpdateUpdateRequest;
use App\Models\Update;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function index()
    {
        $updates = Update::latest()->paginate(30);

        return view('panel.updates.index', compact('updates'));
    }

    public function create()
    {
        return view('panel.updates.create');
    }

    public function store(StoreUpdateRequest $request)
    {
        Update::create([
            'version' => $request->version,
            'title' => $request->title,
            'description' => $request->description,
            'required' => (bool)$request->required,
        ]);

        alert()->success('نسخه جدید با موفقیت ثبت شد','ثبت نسخه جدید');
        return redirect()->route('updates.index');
    }

    public function show(Update $update)
    {
        //
    }

    public function edit(Update $update)
    {
        return view('panel.updates.edit', compact('update'));
    }

    public function update(UpdateUpdateRequest $request,  Update $update)
    {
        $update->update([
            'version' => $request->version,
            'title' => $request->title,
            'description' => $request->description,
            'required' => (bool)$request->required,
        ]);

        alert()->success('نسخه مورد نظر با موفقیت ویرایش شد','ویرایش نسخه');
        return redirect()->route('updates.index');
    }

    public function destroy(Update $update)
    {
        $update->delete();

        return back();
    }
}
