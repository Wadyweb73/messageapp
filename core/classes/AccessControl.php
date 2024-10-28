<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/messageapp/core/init.php');

class AccessControl {
	public static function isLoggedIn() {
		return (new User())->isLoggedIn();
	}

	public static function verifyAccess() {
        $adminRoutes = [
			'/messageapp/admin',
			'/messageapp/admin/teacher/register',
			'/messageapp/admin/teachers',
			'/messageapp/admin/student/register',
			'/messageapp/admin/students',
			'/messageapp/admin/group/{id}/graderecord',
			'/messageapp/admin/graderecords',
			'/messageapp/admin/subject/register',
			'/messageapp/admin/subjects',
			'/messageapp/admin/group/register',
			'/messageapp/admin/groups',
			'/messageapp/admin/group/{id}/students',
			'/messageapp/admin/room/register',
			'/messageapp/admin/rooms',
			'/messageapp/admin/subject/gradelevelsubjects',
			'/messageapp/admin/teachergroups',
		];
		$teacherRoutes = [
            '/messageapp/session',
			'/messageapp/teacher/home',
			'/messageapp/teacher/groups',
            '/messageapp/teacher/studentgrades',
            '/messageapp/teacher/groups',
            '/messageapp/teachers',
            '/messageapp/students',
            '/eduilib/student/{id}',
            '/messageapp/student/group/{group}',
            '/messageapp/studentgrades',
            '/messageapp/studentgrades/pauta/{paua_id}',
            '/messageapp/studentsgrades/{row_id}',
            '/messageapp/subjects',
            '/messageapp/subject/{id}',
            '/messageapp/groups',
            '/messageapp/group/{id}',
            '/messageapp/rooms',
            '/messageapp/room/{id}',
            '/messageapp/gradelevels',
            '/messageapp/gradelevel/{id}',
            '/messageapp/gradelevel/{grade_level_id}/subjects',

            # [VIEWS]
            '/messageapp/teacher/file/form',
            '/messageapp/teacher/files',

            # [DATA]
            '/messageapp/files',
            '/messageapp/file/{file_id}',
            '/messageapp/file/{file_id}/download',
            '/messageapp/file/upload2'
		];
		$studentRoutes = [

		];

        $path = $_SERVER['REQUEST_URI'];

        if ($path === '/messageapp/login' || $path === '/edulib/portal' || '/edulib/admin/graderecords') {
            return;
        }

        if (self::isLoggedIn()) {
            if (in_array($path, $adminRoutes)) {
                if (Session::get(Config::get('session/session_role')) !== 'administrator') {
                    Redirect::to('/messageapp/login'); 
                    exit();
                }
            }
			else if (in_array($path, $teacherRoutes)){
				if (Session::get(Config::get('/session/session_role')) !== 'teacher') {
					Redirect::to('/messageapp/login');
					exit();
				}	
			}
			else if (in_array($path, $studentRoutes)) {

			}
        } else {
            Redirect::to('/messageapp/login');
            exit();
        }
    }

}

?>
