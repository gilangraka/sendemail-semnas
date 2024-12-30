<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendEmailRequest;
use App\Models\SendEmail;
use Illuminate\Http\Request;

class SendEmailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SendEmailRequest $request)
    {
        try {
            $params = $request->validated();

            $last_code = SendEmail::orderByDesc('id')->first()->pluck('code')[0];
            $prefix = substr($last_code, 0, 1);
            $number = (int) substr($last_code, 1);
            $new_number = $number + 1;
            $new_code = $prefix . $new_number;
            $params['code'] = $new_code;

            $data = new SendEmail($params);

            $data->status = 'failed';
            $data->save();
            return response()->json([
                'status' => 400,
                'success' => false,
                'data'  => [
                    'message' => 'Gagal mengirimkan email',
                    'nama' => $data->nama,
                    'email' => $data->email
                ]
            ], 200);


            $data->status = 'success';
            $data->save();
            return response()->json([
                'status' => 200,
                'success' => false,
                'data'  => [
                    'message' => 'Sukses mengirimkan email',
                    'nama' => $data->nama,
                    'email' => $data->email
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
