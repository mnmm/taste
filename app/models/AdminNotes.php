<?php

class AdminNotes extends Eloquent{ 

	public $table = 'admin_notes';
	public $timestamps = false;

    public static function get_adminnotes()
	{
		$admin_notes_all= DB::table('admin_notes')
					->leftjoin('users','admin_notes.user_id', '=', 'users.id')
					->select(DB::raw('admin_notes.id, admin_notes.subject, admin_notes.message, admin_notes.user_id, admin_notes.created, admin_notes.isread, admin_notes.status, admin_notes.type, date(admin_notes.created) as newdate ,users.name as username, users.email as useremail'))
					->orderBy('created', 'desc')
					->get();
		
		return $admin_notes_all;
	
	}

}
