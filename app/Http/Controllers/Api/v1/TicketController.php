<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    public function getTickets()
    {
        return auth()->user()->tickets()->latest()->paginate(10);
    }

    public function getMessages(Request $request)
    {
        $validate = validator()->make($request->all(), [
            'ticket_id' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $ticket = Ticket::find($request->ticket_id);

        if (!$ticket){
            return response()->json([
                'success' => false,
                'message' => 'تیکت مورد نظر موجود نیست'
            ]);
        }

        $messages = $ticket->messages;

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function createTicket(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'title' => 'required',
            'message' => 'required',
            'file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5000'
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $ticket = Ticket::create([
            'sender_id' => auth()->id(),
            'title' => $request->title,
            'code' => $this->generateCode(),
        ]);

        if ($request->file('file')){
            $file = [
                'name' => $request->file('file')->getClientOriginalName(),
                'size' => $request->file('file')->getSize(),
                'type' => $request->file('file')->getClientOriginalExtension(),
                'path' => upload_file($request->file('file'), 'Tickets'),
            ];
        }

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'text' => $request->message,
            'file' => isset($file) ? json_encode($file) : null,
        ]);

        // send notification
        $message1 = 'تیکت شما با موفقیت ثبت شد';
        $message2 = 'یک تیکت با موفقیت ثبت گردید';
        $url = route('tickets.index');
        $user = $ticket->sender;
        $admins = User::where('role','admin')->get();

        Notification::send($user, new SendMessage($message1, $url));
        Notification::send($admins, new SendMessage($message2, $url));
        // end send notification

        return response()->json([
            'success' => true,
            'message' => 'تیکت شما با موفقیت ثبت شد'
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'ticket_id' => 'required',
            'message' => 'required',
            'file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5000'
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $ticket = Ticket::find($request->ticket_id);

        if (!$ticket){
            return response()->json([
                'success' => false,
                'message' => 'تیکت مورد نظر موجود نیست'
            ]);
        }

        if ($request->file('file')) {
            $file = [
                'name' => $request->file('file')->getClientOriginalName(),
                'size' => $request->file('file')->getSize(),
                'type' => $request->file('file')->getClientOriginalExtension(),
                'path' => upload_file($request->file('file'), 'Tickets'),
            ];
        }

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'text' => $request->message,
            'file' => isset($file) ? json_encode($file) : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'پیام شما با موفقیت ارسال شد'
        ]);
    }

    public function generateCode()
    {
        $ticket = Ticket::latest()->first();

        if ($ticket)
        {
            return ++$ticket->code;
        }else{
            return rand(10000000, 99999999);
        }
    }
}
