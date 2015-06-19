<?php

class AdminNotes extends Eloquent{ 

	public $table = 'admin_notes';
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
    
    public function adminnotes()
	{
		$admin_notes_all= DB::table('admin_notes')
					->leftjoin('users','admin_notes.user_id', '=', 'users.id')
					->select(DB::raw('admin_notes.id, admin_notes.subject, admin_notes.message, admin_notes.user_id, admin_notes.created, admin_notes.isread, admin_notes.status, admin_notes.type, date(admin_notes.created) as newdate ,users.fullname as fullname, users.email as useremail'))
					->orderBy('created', 'desc')
					->get();
		
		return $admin_notes_all;
	
	}

}
