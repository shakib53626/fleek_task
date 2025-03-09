<?

namespace App\Classes;

class Helper
{
    public static function sendError($message, $code = 401){

        $response = [
            "success" => false,
            "message" => $message,
        ];

        return response()->json($response, $code);
    }

    public static function sendResponse($data, $message, $code = 200){

        $response = [
            "success" => true,
            "message" => $message,
            "result"  => $data,
        ];

        return response()->json($response, $code);
    }
}
