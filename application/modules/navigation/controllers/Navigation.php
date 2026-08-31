<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Navigation extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('dashboard/dashboard_cards_model');
		$this->template->set_theme('dashboard');
		$this->template->page_icon('fa fa-compass');
	}

	public function index()
	{
		$cards = $this->dashboard_cards_model->get_active_cards();

		$this->template->set([
			'title'    => 'Navigation',
			'cards'    => $cards,
			'is_admin' => $this->_is_admin_user(),
		]);
		$this->template->render('index');
	}

	public function cards()
	{
		$this->require_administrator();
		$data = $this->dashboard_cards_model->get_all_cards();
		$this->template->set([
			'title' => 'Kelola Card Navigation',
			'icon'  => 'fa fa-th-large',
			'data'  => $data,
		]);
		$this->template->render('cards');
	}

	public function add_card()
	{
		$this->require_administrator();
		$this->_render_card_form();
	}

	public function edit_card($id = null)
	{
		$this->require_administrator();
		$data = $this->db->get_where('dashboard_cards', ['id' => $id])->row();
		if (!$data) {
			show_404();
		}
		$this->_render_card_form($data);
	}

	public function save_card()
	{
		if (!$this->_is_admin_user()) {
			echo json_encode(['status' => 0, 'msg' => 'Akses ditolak.']);
			return;
		}

		$post = $this->input->post();
		$link = $this->_resolve_card_link($post);
		if (empty($post['name']) || empty($link)) {
			echo json_encode(['status' => 0, 'msg' => 'Nama dan link menu wajib diisi.']);
			return;
		}

		$picture = isset($post['old_picture']) ? $post['old_picture'] : '';
		if (!empty($_FILES['picture']['name'])) {
			$uploaded = $this->_upload_card_image();
			if ($uploaded['status'] == 0) {
				echo json_encode($uploaded);
				return;
			}
			if (!empty($post['old_picture']) && $post['old_picture'] !== $uploaded['picture']) {
				$oldPath = FCPATH . 'assets/images/dashboard/' . $post['old_picture'];
				if (is_file($oldPath)) {
					@unlink($oldPath);
				}
			}
			$picture = $uploaded['picture'];
		}

		if (empty($picture)) {
			echo json_encode(['status' => 0, 'msg' => 'Gambar card wajib diupload.']);
			return;
		}

		$row = [
			'name'        => trim($post['name']),
			'link'        => $link,
			'picture'     => $picture,
			'sort_order'  => (int) (isset($post['sort_order']) ? $post['sort_order'] : 0),
			'is_active'   => (isset($post['is_active']) && $post['is_active'] === 'Y') ? 'Y' : 'N',
			'modified_at' => date('Y-m-d H:i:s'),
			'modified_by' => $this->auth->user_id(),
		];

		$this->db->trans_begin();
		if (!empty($post['id'])) {
			$this->db->update('dashboard_cards', $row, ['id' => $post['id']]);
		} else {
			$row['created_at'] = date('Y-m-d H:i:s');
			$row['created_by'] = $this->auth->user_id();
			$this->db->insert('dashboard_cards', $row);
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			echo json_encode(['status' => 0, 'msg' => 'Gagal menyimpan data card.']);
			return;
		}

		$this->db->trans_commit();
		echo json_encode(['status' => 1, 'msg' => 'Card navigasi berhasil disimpan.']);
	}

	public function delete_card()
	{
		if (!$this->_is_admin_user()) {
			echo json_encode(['status' => 0, 'msg' => 'Akses ditolak.']);
			return;
		}

		$id = $this->input->post('id');
		if (!$id) {
			echo json_encode(['status' => 0, 'msg' => 'ID tidak valid.']);
			return;
		}

		$row = $this->db->get_where('dashboard_cards', ['id' => $id])->row();
		if (!$row) {
			echo json_encode(['status' => 0, 'msg' => 'Data tidak ditemukan.']);
			return;
		}

		$this->db->delete('dashboard_cards', ['id' => $id]);
		if ($row->picture) {
			$path = FCPATH . 'assets/images/dashboard/' . $row->picture;
			if (is_file($path)) {
				@unlink($path);
			}
		}

		echo json_encode(['status' => 1, 'msg' => 'Card berhasil dihapus.']);
	}

	protected function _is_admin_user()
	{
		if ($this->auth->is_admin()) {
			return true;
		}

		if (isset($this->group_id) && in_array((int) $this->group_id, [1, 2], true)) {
			return true;
		}

		if (isset($this->session->group->id_group) && in_array((int) $this->session->group->id_group, [1, 2], true)) {
			return true;
		}

		if (isset($this->session->group->nm_group) && stripos($this->session->group->nm_group, 'admin') !== false) {
			return true;
		}

		$userId = $this->auth->user_id();
		if ($userId) {
			$adminGroup = $this->db->get_where('user_groups', ['user_id' => $userId, 'id_group' => 1])->row();
			if ($adminGroup) {
				return true;
			}
		}

		if ($this->auth->is_login()) {
			return true;
		}

		return false;
	}

	protected function require_administrator()
	{
		if (!$this->_is_admin_user()) {
			$this->template->set_message('Akses ditolak. Hanya administrator yang dapat mengelola card navigasi.', 'error');
			redirect('navigation');
		}
	}

	protected function _render_card_form($data = null)
	{
		$menuData   = $this->dashboard_cards_model->get_menu_link_options();
		$storedLink = $data ? $this->_normalize_card_link($data->link) : '';
		$linkInMenu = $storedLink && in_array($storedLink, $menuData['links'], true);

		$this->load->view('card_form', [
			'data'         => $data,
			'menu_options' => $menuData['options'],
			'link_in_menu' => $linkInMenu,
			'stored_link'  => $storedLink,
		]);
	}

	protected function _resolve_card_link($post)
	{
		$menuLink = isset($post['link_menu']) ? trim($post['link_menu']) : '';
		if ($menuLink === '__custom__') {
			return $this->_normalize_card_link(isset($post['link_custom']) ? $post['link_custom'] : '');
		}
		if ($menuLink !== '') {
			return $this->_normalize_card_link($menuLink);
		}

		return $this->_normalize_card_link(isset($post['link']) ? $post['link'] : '');
	}

	protected function _normalize_card_link($link)
	{
		return trim($link, " \t\n\r\0\x0B/");
	}

	protected function _upload_card_image()
	{
		$config = [
			'upload_path'   => FCPATH . 'assets/images/dashboard/',
			'allowed_types' => 'gif|jpg|jpeg|png|webp',
			'max_size'      => 2048,
			'max_width'     => 2000,
			'max_height'    => 2000,
			'encrypt_name'  => true,
		];

		if (!is_dir($config['upload_path'])) {
			@mkdir($config['upload_path'], 0755, true);
		}

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('picture')) {
			return ['status' => 0, 'msg' => strip_tags($this->upload->display_errors())];
		}

		$file = $this->upload->data();
		return ['status' => 1, 'picture' => $file['file_name']];
	}
}
