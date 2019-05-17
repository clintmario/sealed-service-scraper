<?php

namespace App\Modules\Home\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Modules\Home\Models\ContactModel;

class ContactController extends Controller
{
    protected $fields = [
        'name',
        'email',
        'subject',
        'message',
    ];

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255|email',
            'subject' => 'required|max:255',
            'message' => 'required|max:2047',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'messages' => $validator->messages()], 200);
        }

        $contact = [];
        foreach ($this->fields as $field) {
            $contact[$field] = $request->input($field);
        }

        $contactModel = new ContactModel();
        $contactModel->insertContact($contact);

        Mail::send("Fortuna::email-contact", ['contact' => $contact], function($message) use ($contact) {
            $message->subject("Contact Message on Bogex from {$contact['email']}")
                ->to('info@bogex.com')
                ->replyTo($contact['email']);
        });

        return response()->json(['status' => 1, 'messages' => 'Message recorded.'], 200);
    }

    public function hello(Request $request)
    {
        return response()->json(['status' => 1, 'messages' => 'Message recorded.'], 200);
    }
}
