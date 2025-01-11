<?php

defined('BASEPATH') or exit('No direct script access allowed');
use Mpdf\Mpdf;

class Checksheets extends Admin_Controller
{
	/*
 * @author Hikmat A.R
 * @copyright Copyright (c) 2023, Hikmat A.R
 */

	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		$this->template->set('title', 'MASTER CHECKSHEET');
	}

	public function index()
	{
		if (!isset($_GET['d']) || $_GET['d'] == '') {
			redirect('checksheets/?d=0');
		}

		$ArrDetail 				= '';
		$selected 				= $checksheets = $details = $breadcumb = $sub = $freq = '';
		$details_data 			= 0;
		$dirs 	  				= $this->db->get_where('checksheets', ['status' => '1', 'company_id' => $this->company])->result();

		if (isset($_GET['d']) && ($_GET['d'])) {
			$checksheets 	  	= $this->db->get_where('checksheets', ['id' => $_GET['d'], 'company_id' => $this->company])->row();
			if ($checksheets) {
				$selected 			= $checksheets->id;
				$details 	  		= $this->db->get_where('view_checksheet_details', ['checksheet_id' => $checksheets->id, 'status' => '1'])->result();
				$breadcumb 			= [($checksheets) ? $checksheets->name : ''];
			}
		}

		if ((isset($_GET['d']) && ($_GET['d'])) && (isset($_GET['sub']) && ($_GET['sub']))) {
			$checksheets		= $this->db->get_where('checksheets', ['id' => $_GET['d'], 'company_id' => $this->company])->row();
			$sub 				= $_GET['sub'];
			$selected 			= $checksheets->id;
			$details 	  		= $this->db->get_where('view_checksheet_details', ['checksheet_id' => $checksheets->id, 'status' => '1'])->row();
			$details_data 	  	= $this->db->get_where('view_checksheet_detail_data', ['checksheet_detail_id' => $sub, 'status' => '1'])->result();
			$breadcumb 			= ($details) ? ["<a href='" . base_url($this->uri->segment(1) . '/?d=' . $checksheets->id) . "'>$details->checksheet_name</a>", $details->checksheet_detail_name] : '';
		}
		$freq 			= [
			'1' => 'Once Time',
			'2' => 'Weekly~Daily',
			'3' => 'Monthly~Daily',
			'4' => 'Weekly~Monthly',
			'5' => 'Yearly~Monthly',
		];

		$this->template->set([
			'data' 				=> $dirs,
			'checksheets' 		=> $checksheets,
			'selected' 			=> $selected,
			'details' 			=> $details,
			'details_data'  	=> $details_data,
			'breadcumb'  		=> $breadcumb,
			'sub'  				=> $sub,
			'freq'  			=> $freq,
		]);

		$this->template->render('index');
	}

	public function save_dir()
	{
		$post 			= $this->input->post();

		if (!isset($post['id'])) {
			$name 			= $post['name'];
			$is_exist 		= $this->db->get_where('checksheets', ['name' => $name, 'company_id' => $this->company])->num_rows();

			if ($is_exist > 0) {
				$return = [
					'status' => 0,
					'msg' => 'Directory Name is already exist',
				];
				echo json_encode($return);
				return false;
			}

			$this->db->trans_begin();
			$data = [
				'name' => strtoupper($name),
				'company_id' => $this->company,
				'created_by' => $this->auth->user_id(),
				'created_at' => date('Y-m-d H:i:s'),
			];

			$this->db->insert('checksheets', $data);
		} else {
			$name 			= $post['name'];
			$data = [
				'name' => strtoupper($name),
				'modified_by' => $this->auth->user_id(),
				'modified_at' => date('Y-m-d H:i:s'),
			];
			$this->db->update('checksheets', $data, ['id' => $post['id']]);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Directory failed created. Query Error'
			);
		} else if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
			$Return		= array(
				'status'		=> 1,
				'msg'			=> 'Directory successfull created'
			);
		} else {
			$this->db->trans_rollback();
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Directory failed created. Data not be inserted'
			);
		}
		echo json_encode($Return);
	}

	public function edit_dir($id)
	{
		if ($id) {
			$data = $this->db->get_where('checksheets', ['id' => $id])->row();
			echo json_encode(['data' => $data]);
		}
	}

	public function delete_dir()
	{
		$id 		 = $this->input->post('id');
		$check_child = $this->db->get_where('checksheet_details', ['checksheet_id' => $id])->num_rows();

		$this->db->trans_begin();

		if ($check_child > 0) {
			$data_detail = $this->db->get_where('checksheet_details', ['checksheet_id' => $id,], ['checksheet_id' => $id])->result();

			$this->db->update('checksheet_details', ['status' => '0'], ['checksheet_id' => $id]);
			foreach ($data_detail as $dtl) {
				$detail_data = $this->db->get_where('checksheet_detail_data', ['checksheet_detail_id' => $dtl->id])->result();

				if (count($detail_data) > 0) {
					$this->db->update('checksheet_detail_data', ['status' => '0'], ['checksheet_detail_id' => $dtl->id]);
				}
			}
		}

		$this->db->update('checksheets', ['status' => '0'], ['id' => $id]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Delete directory failed. Error!'
			);
		} else if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
			$Return		= array(
				'status'		=> 1,
				'msg'			=> 'Delete directory successfull'
			);
		} else {
			$this->db->trans_rollback();
			$Return		= array(
				'status'		=> 0,
				'msg'			=> "Can't Deleted this directory. Data not valid"
			);
		}
		echo json_encode($Return);
	}


	/* SUB DIRECTORY */

	public function edit_sub_dir($id)
	{
		if ($id) {
			$data = $this->db->get_where('checksheet_details', ['id' => $id])->row();
			echo json_encode(['data' => $data]);
		}
	}

	public function save_sub_dir()
	{
		$post 			= $this->input->post();

		if (!isset($post['id'])) {
			$name 			= $post['name'];
			$checksheet_id 		= $post['checksheet_id'];
			$is_exist 		= $this->db->get_where('checksheet_details', ['name' => $name])->num_rows();

			if ($is_exist > 0) {
				$return = [
					'status' => 0,
					'msg' => 'Directory Name is already exist',
				];
				echo json_encode($return);
				return false;
			}

			$this->db->trans_begin();
			$data = [
				'name' 			=> ucfirst($name),
				'company_id' 	=> $this->company,
				'checksheet_id' 		=> $checksheet_id,
				'created_by' 	=> $this->auth->user_id(),
				'created_at' 	=> date('Y-m-d H:i:s'),
			];

			$this->db->insert('checksheet_details', $data);
		} else {
			$name	= $post['name'];
			$data 	= [
				'name' => strtoupper($name),
				'modified_by' => $this->auth->user_id(),
				'modified_at' => date('Y-m-d H:i:s'),
			];
			$this->db->update('checksheet_details', $data, ['id' => $post['id']]);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Directory failed created. Query Error'
			);
		} else if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
			$Return		= array(
				'status'		=> 1,
				'msg'			=> 'Directory successfull created'
			);
		} else {
			$this->db->trans_rollback();
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Directory failed created. Data not be inserted'
			);
		}
		echo json_encode($Return);
	}


	public function delete_sub_dir()
	{
		$id 		 = $this->input->post('id');
		$check_child = $this->db->get_where('checksheet_detail_data', ['checksheet_detail_id' => $id])->result();

		$this->db->trans_begin();

		if (count($check_child) > 0) {
			$data_sheet = $this->db->get_where('checksheet_detail_data', ['checksheet_detail_id' => $id])->result();
			foreach ($data_sheet as $sheet) {
				$check_items = $this->db->get_where('checksheet_data_items', ['checksheet_data_number' => $sheet->number])->result();
				if (count($check_items) > 0) {
					$this->db->update('checksheet_data_items', ['status' => '0'], ['checksheet_data_number' => $sheet->number]);
				}
			}
		}

		$this->db->update('checksheet_details', ['status' => '0'], ['id' => $id]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Delete directory failed. Error!'
			);
		} else if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
			$Return		= array(
				'status'		=> 1,
				'msg'			=> 'Delete directory successfull'
			);
		} else {
			$this->db->trans_rollback();
			$Return		= array(
				'status'		=> 0,
				'msg'			=> "Can't Deleted this directory. Data not valid"
			);
		}
		echo json_encode($Return);
	}



	/* ADD ITEMS */

	public function upload_file()
	{
		$checksheet_detail_id = $this->input->get('dtl');
		$this->template->set([
			'checksheet_detail_id' 	=> $checksheet_detail_id,
		]);
		$this->template->render('form');
	}


	private function getNumber()
	{
		$last_number 	= $this->db->select('MAX(RIGHT(number,3)) as number')->from('checksheet_detail_data')->get()->row();
		$counter 		= 1;
		if ($last_number->number) {
			$counter = $last_number->number + 1;
		}
		$new_number = "CS" . date('y') . "-" . sprintf("%03d", $counter);
		return $new_number;
	}

	public function upload_document()
	{
		$data 						= $this->input->post();

		$items = $data['items'];
		unset($data['items']);
		if ($data) {
			try {
				$id							= isset($data['id']) ? $data['id'] : '';
				$data['company_id']			= $this->company;

				if (!isset($data['number'])) {
					$data['number'] = $this->getNumber();
				}

				$this->db->trans_begin();
				$check = $this->db->get_where('checksheet_detail_data', ['id' => $id])->num_rows();

				if (intval($check) == '0') {
					$data['created_by']		= $this->auth->user_id();
					$data['created_at']		= date('Y-m-d H:i:s');
					$this->db->insert('checksheet_detail_data', $data);
					$detail_id = $this->db->order_by('id', 'DESC')->get('checksheet_detail_data')->row()->id;
				} else {
					$data['modified_by']	= $this->auth->user_id();
					$data['modified_at']	= date('Y-m-d H:i:s');
					$this->db->update('checksheet_detail_data', $data, ['id' => $id]);
					$detail_id = $data['id'];
				}

				if ($items) {
					foreach ($items as $item) {
						if (isset($item['id'])) {
							$item['modified_by'] = $this->auth->user_id();
							$item['modified_at'] = date('Y-m-d H:i:s');
							$this->db->update('checksheet_data_items', $item, ['id' => $item['id']]);
							$id_item = $item['id'];
							$check_checksheet = $this->db->select('checksheet_data_number,number')->from('checksheet_process_data')
								->join('checksheet_process_dir', 'checksheet_process_data.dir_id = checksheet_process_dir.id')
								->where(['checksheet_process_dir.status' => '1', 'checksheet_process_data.checksheet_data_number' => $data['number'], 'YEAR(checksheet_process_data.periode)' => date('Y')])
								->get()->result();

							if (count($check_checksheet) > 0) {
								unset($item['modified_by']);
								unset($item['modified_at']);
								unset($item['checksheet_data_number']);
								unset($item['id']);
								foreach ($check_checksheet as $insert_item) {
									$where = ['checksheet_item_id' => $id_item, 'checksheet_process_data_number' => $insert_item->number];
									$this->db->update('checksheet_process_details', $item, $where);
								}
							}

							// $this->db->update('checksheet_process_details', [
							// 	'item_name' => $item['item_name'],
							// 	'standard_check' => $item['standard_check'],
							// 	'check_type' => $item['check_type'],
							// ], ['checkheet_item_id' => $item['id']]);

						} else {
							$item['created_by'] = $this->auth->user_id();
							$item['created_at'] = date('Y-m-d H:i:s');
							$item['checksheet_data_number'] = $data['number'];
							$this->db->insert('checksheet_data_items', $item);
							$id_item = $this->db->insert_id();
							$check_checksheet = $this->db->select('checksheet_data_number,number')->from('checksheet_process_data')
								->join('checksheet_process_dir', 'checksheet_process_data.dir_id = checksheet_process_dir.id')
								->where(['checksheet_process_dir.status' => '1', 'checksheet_process_data.checksheet_data_number' => $data['number'], 'YEAR(checksheet_process_data.periode)' => date('Y')])
								->get()->result();

							if (count($check_checksheet) > 0) {
								unset($item['created_by']);
								unset($item['created_at']);
								unset($item['checksheet_data_number']);
								foreach ($check_checksheet as $insert_item) {
									$item['checksheet_item_id'] = $id_item;
									$item['checksheet_process_data_number'] = $insert_item->number;
									$this->db->insert('checksheet_process_details', $item);
								}
							}
						}
					}
				}

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$Return = [
						'status' => 0,
						'msg'	 => 'Failed save item check. Please try again later.!'
					];
				} else {
					$this->db->trans_commit();
					$Return = [
						'status' => 1,
						'msg'	 => 'Success save item check...'
					];
				}
				echo json_encode($Return);
			} catch (Exception $e) {
				$this->db->trans_rollback();
				$Return = [
					'status' => 0,
					'msg'	 => $e->getMessage()
				];

				echo json_encode($Return);
				return false;
			}
		} else {
			$Return = [
				'status' => 0,
				'msg'	 => 'Data not valid!'
			];
			echo json_encode($Return);
		}
	}

	public function edit_file($id)
	{

		$data = [];
		if ($id) {
			$data 					= $this->db->get_where('checksheet_detail_data', ['id' => $id])->row();
			$data_item 	 			= $this->db->get_where('checksheet_data_items', ['checksheet_data_number' => $data->number, 'status' => '1'])->result();

			$this->template->set([
				'data' 				=> $data,
				'data_item' 		=> $data_item,
			]);
			$this->template->render('edit');
		} else {
			echo "Data not found";
		}
	}

	public function load_file($id)
	{
		$data = [];
		if ($id) {
			$data = $this->db->get_where('view_checksheet_detail_data', ['id' => $id])->row();

			$return = [
				'status' => 1,
				'data' => $data
			];
		} else {
			$return = [
				'status' => 0,
				'data' => $data
			];
		}

		echo json_encode($return);
	}

	public function view_file($id)
	{
		$data 					= $this->db->get_where('checksheet_detail_data', ['id' => $id])->row();
		$data_item 	 			= $this->db->get_where('checksheet_data_items', ['checksheet_data_number' => $data->number, 'status' => '1'])->result();
		$freq 			= [
			'1' => 'Once Time',
			'2' => 'Weekly~Daily',
			'3' => 'Monthly~Daily',
			'4' => 'Weekly~Monthly',
			'5' => 'Yearly~Monthly',
		];
		$this->template->set([
			'data' 				=> $data,
			'data_item' 		=> $data_item,
			'freq' 				=> $freq,
		]);

		$this->template->render('view-file');
	}

	public function delete_sheet()
	{
		$id 		 = $this->input->post('id');
		if ($id) {
			$data 		 = $this->db->get('checksheet_detail_data')->row();
			$check_child = $this->db->get_where('checksheet_data_items', ['checksheet_data_number' => $data->number])->result();

			$this->db->trans_begin();
			if (count($check_child) > 0) {
				$this->db->update('checksheet_data_items', ['status' => '0'], ['checksheet_data_number' => $data->number]);
			}
			$this->db->update('checksheet_detail_data', ['status' => '0'], ['id' => $id]);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$Return		= array(
					'status'		=> 0,
					'msg'			=> 'Delete sheet failed. Error!'
				);
			} else if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				$Return		= array(
					'status'		=> 1,
					'msg'			=> 'Delete sheet successfull'
				);
			} else {
				$this->db->trans_rollback();
				$Return		= array(
					'status'		=> 0,
					'msg'			=> "Can't Deleted this sheet. Data not valid"
				);
			}
			echo json_encode($Return);
		} else {
			$Return		= array(
				'status'		=> 0,
				'msg'			=> "Data not valid"
			);
			echo json_encode($Return);
		}
	}

	public function delete_item()
	{
		$id 		 = $this->input->post('id');
		if ($id) {
			$this->db->trans_begin();
			$this->db->update('checksheet_data_items', ['status' => '0'], ['id' => $id]);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$Return		= array(
					'status'		=> 0,
					'msg'			=> 'Delete item failed. Error!'
				);
			} else if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				$Return		= array(
					'status'		=> 1,
					'msg'			=> 'Delete item successfull'
				);
			} else {
				$this->db->trans_rollback();
				$Return		= array(
					'status'		=> 0,
					'msg'			=> "Can't Deleted this item. Data not valid"
				);
			}
			echo json_encode($Return);
		} else {
			$Return		= array(
				'status'		=> 0,
				'msg'			=> "Data not valid"
			);
			echo json_encode($Return);
		}
	}

	/* ===================== */
	public function print_document()
	{
		
		$folder = $_GET['p'];
		$file = $_GET['f'];

		$mpdf = new Mpdf();
		$mpdf->SetImportUse();
		$pagecount = $mpdf->SetSourceFile('directory/' . $folder . '/' . $file);
		$tplId = $mpdf->ImportPage($pagecount);
		$mpdf->UseTemplate($tplId);
		$mpdf->addPage();
		$mpdf->WriteHTML('Hello World');
		$newfile = 'directory/' . $folder . '/' . $file;
		$mpdf->Output($newfile, 'F');
		$mpdf->Output();
	}
}
