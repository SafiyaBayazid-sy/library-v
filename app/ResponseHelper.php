<?php

namespace App;

class ResponseHelper{
    static function success($message ="تمت العملية بنجاح" , $data = null, $code = 200 ){
        return [
            'success' => true,
            'code'=>$code,
            'message' => $message,
            'data' => $data,
        ];
    }
    static function failed($message ="فشلت العملية" , $data = null,$code = 403){
        return [
            'success' => false,
            'code'=>$code,
            'message' => $message,
            'data' => $data,
        ];
    }
}
