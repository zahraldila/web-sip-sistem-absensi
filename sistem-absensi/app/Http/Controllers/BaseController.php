<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function success($data = null, $message = 'OK')
    {
        return response()->json(['status' => 'success', 'message' => $message, 'data' => $data]);
    }

    protected function error($message = 'Error', $code = 400)
    {
        return response()->json(['status' => 'error', 'message' => $message], $code);
    }
}
