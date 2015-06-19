<?php

class Logs extends Eloquent{ 

	public $table = 'admin_logs';
	public $timestamps = false;

	public static function save_log($data) 
	{
		return $id = DB::table('admin_logs')->insertdelayed($data);
	}


	public static function sendadminnotes($sub,$message ,$user_id,$type='')
    {
       $created=date('Y-m-d H:i:s');
	   $adminnoteid = DB::table('admin_notes')->insertGetId(
    			array('subject' => $sub, 'message' => $message, 'user_id' => $user_id, 'created' => $created,'type' => $type)
			);
	  return $adminnoteid;
	   
    }

}
