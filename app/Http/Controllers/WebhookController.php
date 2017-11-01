<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle_webhook_btc(Request $request)
    {
        Log::info($request);
    }
}
