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

    public function create()
    {
        //
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
        // prevent from send sequence notification
//        $first_message = $ticket->messages()->orderBy('created_at', 'desc')->first();
//        if ($first_message != null && $first_message->user_id != auth()->id()){
//            $message = 'پاسخی برای تیکت "'.$ticket->title.'" ثبت شده است';
//            $url = route('tickets.edit', $ticket->id);
//            $receiver = auth()->id() == $ticket->sender_id ? $ticket->receiver : $ticket->sender;
//            Notification::send($receiver, new SendMessage($message, $url));
//        }
        // end prevent from send sequence notification

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

        // send notif
//            $status = Ticket::STATUS[$ticket->status];
//            $message = "وضعیت تیکت '$ticket->title' به '$status' تغییر یافت";
//            $url = route('tickets.index');
//            $receiver = auth()->id() == $ticket->sender_id ? $ticket->receiver : $ticket->sender;
//            Notification::send($receiver, new SendMessage($message, $url));
        // end send notif

        alert()->success('وضعیت تیکت با موفقیت تغییر یافت','تغییر وضعیت');
        return back();
    }
}
