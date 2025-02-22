<?php

namespace App\Http\Controllers;

use App\Models\InteraktCallback;
use Illuminate\Http\Request;

class InteraktCallbackController extends Controller
{
    public function store(Request $request)
    {
        // Decode the incoming JSON (Laravel does this automatically for JSON requests)
        $data = $request->all();

        // Extract individual details using array access or the data_get helper
        $name         = data_get($data, 'data.customer.traits.name');
        $phone_number = data_get($data, 'data.customer.phone_number');
        $status       = data_get($data, 'data.message.message_status');
        $failedReason = data_get($data, 'data.message.channel_failure_reason');
        $messageId    = data_get($data, 'data.message.id');
        $receivedAt   = data_get($data, 'data.message.received_at_utc');

        // Create a new record in the database
        InteraktCallback::create([
            'name'         => $name,
            'phone_number' => $phone_number,
            'status'       => $status,
            'failed_reason' => $failedReason,
            'message_id'   => $messageId,
            'received_at'  => $receivedAt,
            'full_json'    => $data, // Laravel will automatically convert this to JSON
        ]);

        // Return a response if needed
        return response()->json(['message' => 'Callback saved successfully'], 200);
    }
}
