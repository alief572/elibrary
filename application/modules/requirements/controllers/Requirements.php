<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Syamsudin
 * @copyright Copyright (c) 2021, Syamsudin
 *
 * This is controller for Folders
 */

class Requirements extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('download');
		$this->load->library(array('upload', 'Image_lib'));
		$this->load->model(array(
			'Aktifitas/aktifitas_model'
		));

		$this->template->set('title', 'Requirements');
		$this->template->set('icon', 'fa fa-cog');

		date_default_timezone_set("Asia/Bangkok");
	}

	public function index()
	{
		$data			= $this->db->get_where('requirements', ['deleted_at' => null, 'status' => '1'])->result();
		$dataDraft		= $this->db->get_where('requirements', ['deleted_at' => null, 'status' => 'DFT'])->result();
		$this->template->set('title', 'Index of Standard');
		$this->template->set('data', $data);
		$this->template->set('dataDraft', $dataDraft);
		$this->template->render('index');
	}

	public function add()
	{
		$this->template->set('title', 'Add New Standard');
		$this->template->render('add');
	}

	public function edit($id = '')
	{
		$Data 		= $this->db->get_where('requirements', ['id' => $id, 'status !=' => '0'])->row();
		if ($Data) {
			$Data_list 	= $this->db->get_where('requirement_details', ['requirement_id' => $id])->result();

			$this->template->set([
				'Data' => $Data,
				'Data_list' => $Data_list,
			]);

			$this->template->render('edit');
		} else {
			$data = [
				'heading' => 'Error!',
				'message' => 'Data not found..'
			];
			$this->template->render('../views/errors/html/error_404_custome', $data);
		}
	}

	public function edit_detail($id = '')
	{
		$Data_list 	= $this->db->get_where('requirement_details', ['id' => $id])->row();
		echo  json_encode($Data_list);
	}

	public function view($id = '')
	{
		$Data 	= $this->db->get_where('requirements', ['id' => $id, 'status' => '1'])->row();
		$Data_list 	= $this->db->get_where('requirement_details', ['requirement_id' => $id])->result();

		$this->template->set([
			'Data' => $Data,
			'List' => $Data_list
		]);

		$this->template->render('view');
	}

	public function view_detail($id = '')
	{
		$Data_list 	= $this->db->get_where('requirement_details', ['id' => $id])->row();
		echo  json_encode($Data_list);
	}


	public function save()
	{
		$Data 		= $this->input->post();
		$Data_list 	= (isset($Data['list'])) ? $Data['list'] : '';
		unset($Data['DataTables_Table_0_length']);
		$this->db->trans_begin();
		if ($Data) {
			$Data['company_id'] = $this->company;
			unset($Data['list']);
			if (isset($Data['id'])) {
				$Data['modified_by'] = $this->auth->user_id();
				$Data['modified_at'] = date('Y-m-d H:i:s');
				$this->db->update('requirements', $Data, ['id' => $Data['id']]);
			} else {
				$Data['created_by'] = $this->auth->user_id();
				$Data['created_at'] = date('Y-m-d H:i:s');
				$this->db->insert('requirements', $Data);
			}
		}

		if (isset($Data['id'])) {
			$Data_list['requirement_id'] = $Data['id'];
		} else {
			$req = $this->db->order_by('id', 'DESC')->get_where('requirements')->row();
			$Data_list['requirement_id'] = $req->id;
		}
		if ($Data_list) {

			if (isset($Data_list['id'])) {
				$Data_list['modified_by'] = $this->auth->user_id();
				$Data_list['modified_at'] = date('Y-m-d H:i:s');
				$this->db->update('requirement_details', $Data_list, ['id' => $Data_list['id']]);
			} else {
				$Data_list['created_by'] = $this->auth->user_id();
				$Data_list['created_at'] = date('Y-m-d H:i:s');
				$this->db->insert('requirement_details', $Data_list);
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Data Chapter failed to save. Please try again.',
			);
		} else {
			$this->db->trans_commit();
			$Return		= array(
				'status'		=> 1,
				'msg'			=> 'Data Chapter successfully saved..',
				'id'			=> $Data_list['requirement_id']
			);
		}

		echo json_encode($Return);
	}

	public function delete($id)
	{
		$this->db->trans_begin();
		if (($id)) {
			$this->db->delete('requirements', ['id' => $id]);
			$this->db->delete('requirement_details', ['requirement_id' => $id]);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Failed to delete data.. Please try again.',
			);
		} else {
			$this->db->trans_commit();
			$Return		= array(
				'status'		=> 1,
				'msg'			=> 'Successfully deleted data..',
			);
		}

		echo json_encode($Return);
	}

	public function delete_pasal($id)
	{
		$this->db->trans_begin();
		if (($id)) {
			$this->db->delete('requirement_details', ['id' => $id]);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Failed to delete data.. Please try again.',
			);
		} else {
			$this->db->trans_commit();
			$Return		= array(
				'status'		=> 1,
				'msg'			=> 'Successfully deleted data..',
			);
		}

		echo json_encode($Return);
	}
}
