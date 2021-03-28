<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Libraries\Colors;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    const LIMIT = 10;
    const MAX_BODY_LENGTH = 140;
    const MAX_TO_LENGTH = 24;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Message::paginate(MessageController::LIMIT);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'to' => 'bail|required|max:' . self::MAX_TO_LENGTH,
            'body' => 'bail|required|max:' . self::MAX_BODY_LENGTH,
            'color' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) {
                    if (!Colors::isColorAllowed($value)) {
                        $fail('The ' . $attribute . ' is invalid.');
                    }
                }
            ]
        ]);


        return Message::create($validated);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        $message->increment('views');
        return $message;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
