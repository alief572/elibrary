<?php defined('BASEPATH') || exit('No direct script access allowed');

class Menu_generators
{
	protected $ci;
	protected $x; //Db Prefix
	protected $uri; //Current uri string
	protected $user_id;
	protected $is_admin;

	public function __construct()
	{
		$this->ci = &get_instance();

		$this->x = $this->ci->db->dbprefix;
		$this->uri = '/' . $this->ci->uri->uri_string() . '/';

		$this->ci->load->helper('app');
		//$this->ci->load->model('menus/users_model');
		$this->ci->load->library('users/auth');
		$this->user_id 	= $this->ci->auth->user_id();
		$this->is_admin = $this->ci->auth->is_admin();
	}

	protected function get_auth_permission($user_id = 0)
	{
		$role_permissions = $this->ci->users_model->select("permissions.id_permission")
			->join("user_groups", "users.id_user = user_groups.id_user")
			->join("group_permissions", "user_groups.id_group = group_permissions.id_group")
			->join("permissions", "group_permissions.id_permission = permissions.id_permission")
			->where("users.id_user", $user_id)
			->find_all();

		$user_permissions = $this->ci->users_model->select("permissions.id_permission")
			->join("user_permissions", "users.id_user = user_permissions.id_user")
			->join("permissions", "user_permissions.id_permission = permissions.id_permission")
			->where("users.id_user", $user_id)
			->find_all();

		$merge = array();
		if ($role_permissions) {
			foreach ($role_permissions as $key => $rp) {
				if (!in_array($rp->id_permission, $merge)) {
					$merge[] = $rp->id_permission;
				}
			}
		}

		if ($user_permissions) {
			foreach ($user_permissions as $key => $up) {
				if (!in_array($up->id_permission, $merge)) {
					$merge[] = $up->id_permission;
				}
			}
		}

		return $merge;
	}
}
