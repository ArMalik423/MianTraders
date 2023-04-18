<?php

namespace App\Http\Traits;

trait ApiResponse
{

    public function success($msg, $data = [], $statusCode = 200)
    {
        $response = (isset($msg['status']) && trim($msg['status']) == 'success') ? $msg : $this->response('success', $msg);
        return response()->json(array_merge($response, $data), $statusCode);
    }

    public function error($msg, $errors = [], $statusCode = 200)
    {
//        return $errors;
//        if (!is_countable($msg)) {
//            $msg = [$msg];
//        }
        $response = [];
        if(isset($errors['errors']) && !empty($errors['errors'])){
            $data = ($errors['errors']->toArray());
            foreach ($data as $item) {
                foreach ($item as $li) {
                    array_push($response, $li);
                }
            }
        }else{
            array_push($response, $msg);
        }

        $errors['errors'] = $response;

        return response()->json(array_merge(['status' => 'error', 'message' => $msg], $errors), $statusCode);
    }

//    public function error($msg, $errors = [], $statusCode = 200)
//    {
//        if (!is_countable($msg)) {
//            $msg = [$msg];
//        }
//        return response()->json(array_merge(['status' => 'error', 'message' => $msg], $errors), $statusCode);
//    }

    public function response($status = 'error', $msg = 'something went wrong!')
    {
        return ['status' => $status, 'message' => $msg];
    }
}
