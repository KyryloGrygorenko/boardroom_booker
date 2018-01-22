<?php
namespace Library;

abstract class Boardroom
{
    public static $boardroom_id;


    public static function setBoardoomId($request){
        if(self::$boardroom_id = $request->get('boardroom_id')){
            setcookie('boardroom_id', self::$boardroom_id, time() + 60 * 60 * 24 * 366, '/');
        }elseif (isset($_COOKIE['boardroom_id'])){
            self::$boardroom_id=$_COOKIE['boardroom_id'];
        }else{
            self::$boardroom_id=1;
        }
    }

}