<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function getTickets()
    {
        return Ticket::latest()->paginate(10);
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

        $file = [
            'name' => $request->file('file')->getClientOriginalName(),
            'size' => $request->file('file')->getSize(),
            'type' => $request->file('file')->getClientOriginalExtension(),
            'path' => upload_file($request->file('file'), 'Tickets'),
        ];

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'text' => $request->message,
            'file' => json_encode($file),
        ]);

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

        $file = [
            'name' => $request->file('file')->getClientOriginalName(),
            'size' => $request->file('file')->getSize(),
            'type' => $request->file('file')->getClientOriginalExtension(),
            'path' => upload_file($request->file('file'), 'Tickets'),
        ];

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'text' => $request->message,
            'file' => json_encode($file),
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
