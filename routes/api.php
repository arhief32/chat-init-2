<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('post-approve', function(Request $request){
    $options = array(
        'cluster' => 'ap1',
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        '74f634a0084f2960c016',
        'eb25feecd3a9d460316e',
        '647989',
        $options
    );

    $data = $request->all();
    $pusher->trigger(
        'channel-approve-'.$request->user_id, 
        'event-approve-'.$request->user_id, $data);
});

Route::post('send-message', function(Request $request){
    $options = array(
        'cluster' => 'ap1',
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        '74f634a0084f2960c016',
        'eb25feecd3a9d460316e',
        '647989',
        $options
    );

    $data = $request->all();
    $pusher->trigger(
        'channel-message-'.$request->conversation_id, 
        'event-message-'.$request->conversation_id, $data);    
    
});

Route::post('break-conversation', function(Request $request){
    $options = array(
        'cluster' => 'ap1',
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        '74f634a0084f2960c016',
        'eb25feecd3a9d460316e',
        '647989',
        $options
    );

    $data = $request->all();
    $pusher->trigger(
        'channel-break-conversation-'.$request->id, 
        'event-break-conversation-'.$request->id, $data);
});