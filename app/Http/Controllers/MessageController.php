<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::all();
        return view('messages.index', compact('messages'));
    }

    public function create()
    {
        return view('messages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'email' => 'required|email',
            'sujet' => 'required',
            'message' => 'required',
        ]);

        Message::create($request->all());
        return redirect()->route('messages.create')->with('success', 'Message envoyé !');
    }
}
