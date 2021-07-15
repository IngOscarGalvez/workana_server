<?php

namespace App\Traits;

trait ApiResponser
{

    private function successResponse($status, $code, $messages, $result)
    {
        return response()->json(
            [
                'status'   => $status,
                'code'     => $code,
                'messages' => $messages,
                'result'   => $result,
            ],
            $code
        );
    }

    protected function errorResponse($status, $code, $messages)
    {
        return response()->json(
            [
                'status'   => $status,
                'code'     => $code,
                'messages' => $messages,
                'result'   => null,
            ],
            $code
        );
    }
}
