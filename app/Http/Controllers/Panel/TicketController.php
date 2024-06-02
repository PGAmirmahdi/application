<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::latest()->paginate(30);

        return view('panel.tickets.index', compact('tickets'));
    }

    public function create(Ticket $ticket)
    {
        return view('panel.tickets.create', compact('ticket'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Ticket $ticket)
    {
        //
    }

    public function edit(Ticket $ticket)
    {
        return view('panel.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ($request->file){
            $file_info = [
                'name' => $request->file('file')->getClientOriginalName(),
                'type' => $request->file('file')->getClientOriginalExtension(),
                'size' => $request->file('file')->getSize(),
            ];

            $file = upload_file($request->file, 'Messages');

            $file_info['path'] = $file;
        }

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'text' => $request->text,
            'file' => isset($file) ? json_encode($file_info) : null,
        ]);

        // send notification
        $message = "تیکت شما با عنوان '{$ticket->title}' پاسخ داده شد";
        $url = route('tickets.index');
        $user = $ticket->sender;

        Notification::send($user, new SendMessage($message, $url));
        // end send notification

        return back();
    }

    public function destroy(Ticket $ticket)
    {
        //
    }

    public function changeStatus(Ticket $ticket)
    {
        if ($ticket->status == 'closed'){
            $ticket->update(['status' => 'pending']);
        }else{
            $ticket->update(['status' => 'closed']);
        }

        alert()->success('وضعیت تیکت با موفقیت تغییر یافت','تغییر وضعیت');
        return back();
    }
}
