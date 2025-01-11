<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Regulations extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('download');
		$this->load->library(array('upload', 'Image_lib'));
		$this->load->model('regulations/Regulations_model', 'RegModel');

		$this->template->set('title', 'Index of Regulations');
		$this->template->set('icon', 'fa fa-cog');

		date_default_timezone_set("Asia/Bangkok");
	}

	public function index()
	{
		$data			= $this->db->where_in('status', ['PUB', 'EXP', 'CH'])->get('view_regulations')->result();
		$drafts			= $this->db->get_where('view_regulations', ['status' => 'DFT'])->result();

		$dataRegSjb 	= $this->db->get_where('regulation_subjects')->result();
		$dataRegScp 	= $this->db->get_where('regulation_scopes')->result();

		$ArrRegSjb = [];
		foreach ($dataRegSjb as $dtRegSjb) {
			$ArrRegSjb[$dtRegSjb->regulation_id][] = $dtRegSjb;
		}

		$ArrRegScp = [];
		foreach ($dataRegScp as $dtRegScp) {
			$ArrRegScp[$dtRegScp->regulation_id][] = $dtRegScp;
		}

		$status = [
			'PUB' => 'Berlaku',
			'EXP' => 'Dicabut',
			'CH' => 'Diubah',
		];

		$listReg 	= $this->db->get_where('view_regulations', ['status' => 'PUB'])->result();
		$ArrReg = [];
		foreach ($listReg as $reg) {
			$ArrReg[$reg->id] = $reg->category_name . " " . $reg->nomenclature . (($reg->number) ? " No. " . $reg->number : '') . " " . $reg->year;
		}

		$this->template->set('data', $data);
		$this->template->set('drafts', $drafts);
		$this->template->set('ArrRegSjb', $ArrRegSjb);
		$this->template->set('status', $status);
		$this->template->set('ArrRegScp', $ArrRegScp);
		$this->template->set('ArrReg', $ArrReg);
		$this->template->render('index');
	}

	public function add()
	{
		$category 	= $this->db->get_where('regulation_category')->result();
		$scopes 	= $this->db->get_where('scopes')->result();
		$subjects 	= $this->db->get_where('subjects')->result();
		$listReg 	= $this->db->get_where('view_regulations', ['status' => 'PUB'])->result();

		$this->template->set([
			'title' 	=> 'Add New Regulation',
			'category' 	=> $category,
			'scopes' 	=> $scopes,
			'listReg' 	=> $listReg,
			'subjects' 	=> $subjects,
		]);
		$this->template->render('add');
	}

	public function edit($id = '')
	{
		$Data 		= $this->db->get_where('regulations', ['id' => $id])->row();
		$category 	= $this->db->get_where('regulation_category')->result();
		$scopes 	= $this->db->get_where('scopes')->result();
		$subjects 	= $this->db->get_where('subjects')->result();

		$dataRegSjb 	= $this->db->get_where('regulation_subjects')->result();
		$dataRegScp 	= $this->db->get_where('regulation_scopes')->result();

		$ArrRegSjb = [];
		foreach ($dataRegSjb as $dtRegSjb) {
			$ArrRegSjb[$dtRegSjb->regulation_id][$dtRegSjb->subject_id] = $dtRegSjb->subject_id;
		}

		$ArrRegScp = [];
		foreach ($dataRegScp as $dtRegScp) {
			$ArrRegScp[$dtRegScp->regulation_id][$dtRegScp->scope_id] = $dtRegScp->scope_id;
		}

		$pasal 					= $this->db->order_by('order', 'ASC')->get_where('regulation_pasal', ['regulation_id' => $id])->result();
		$regulation_paragraphs 	= $this->db->order_by('order', 'ASC')->get_where('regulation_paragraphs', ['regulation_id' => $id])->result();

		$ArrPhar = [];
		foreach ($regulation_paragraphs as $regPh) {
			$ArrPhar[$regPh->pasal_id][] = $regPh;
		}

		$listReg = $this->db->get_where('view_regulations', ['status' => 'PUB'])->result();


		if ($Data) {
			$this->template->set([
				'title' 		=> 'Edit Regulation',
				'data' 			=> $Data,
				'category' 		=> $category,
				'scopes' 		=> $scopes,
				'subjects' 		=> $subjects,
				'ArrRegSjb' 	=> $ArrRegSjb,
				'ArrRegScp' 	=> $ArrRegScp,
				'pasal' 		=> $pasal,
				'listReg' 		=> $listReg,
				'ArrPhar' 		=> $ArrPhar,
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

	/* Pasal */
	public function add_pasal($reg_id = null)
	{
		$this->template->set([
			'reg_id' => $reg_id
		]);
		$this->template->render('form-pasal');
	}

	public function edit_pasal($id = null)
	{

		if ($id) {
			$data = $this->db->get_where('regulation_pasal', ['id' => $id])->row();
			$this->template->set([
				'data' => $data
			]);

			$this->template->render('form-pasal');
		}
	}

	public function load_form($id = '')
	{
		$data = '';
		$desc = '';

		if ($id) {
			$data 	= $this->db->get_where('regulation_pasal', ['id' => $id])->row();
			$desc 	= $this->db->get_where('regulation_paragraphs', ['pasal_id' => $id])->result();
		}

		$this->template->set([
			'data' => $data,
			'desc' => $desc,
		]);

		$this->template->render('form');
	}

	public function load_form_edit($id = '')
	{
		$desc = '';
		if ($id) {
			$desc 	= $this->db->get_where('regulation_paragraphs', ['id' => $id])->row();
		}

		$this->template->set([
			'desc' => $desc,
		]);

		$this->template->render('form-edit');
	}


	/* ======== */

	public function view($id = '')
	{
		$Data 	= $this->db->get_where('regulations', ['id' => $id])->row();
		$Pasal 	= $this->db->get_where('regulation_pasal', ['regulation_id' => $id])->result();
		$Desc 	= $this->db->order_by('order', 'ASC')->get_where('regulation_paragraphs', ['regulation_id' => $id])->result();

		$ArrDesc = [];
		foreach ($Desc as $dsc) {
			$ArrDesc[$dsc->pasal_id][] = $dsc;
		}

		$this->template->set([
			'Data' 		=> $Data,
			'Pasal' 	=> $Pasal,
			'ArrDesc' 	=> $ArrDesc
		]);
		// echo '<pre>';
		// print_r($ArrDesc);
		// echo '<pre>';
		// exit;

		$this->template->render('view');
	}

	public function view_detail($id = '')
	{
		$Data_list 	= $this->db->get_where('requirement_details', ['id' => $id])->row();
		echo  json_encode($Data_list);
	}

	public function loadForm()
	{
		$this->template->render('chapter_form');
	}

	public function save_pasal()
	{
		$Data = $this->input->post();

		if ($Data) {
			$checkname = $this->db->get_where('regulation_pasal', ['name' => $Data['name'], 'regulation_id' => $Data['regulation_id']])->num_rows();
			if ($checkname > 1) {
				$Return		= array(
					'status'		=> 2,
					'msg'			=> 'Pasal name "' . $Data['name'] . '" already exist, please use another name!',
				);
				echo json_encode($Return);
				return false;
			}
			$this->db->trans_begin();
			$this->RegModel->savePasal($Data);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$Return		= array(
					'status'		=> 0,
					'msg'			=> 'Failed to save pasal, please try again!',
				);
			} else {
				$this->db->trans_commit();
				$Return		= array(
					'status'		=> 1,
					'msg'			=> 'Successfull to save pasal',
				);
			}
		} else {
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Data not valid, please try again!',
			);
		}

		echo json_encode($Return);
	}

	public function save()
	{
		$Data 		= $this->input->post();
		if ($Data) {
			$this->db->trans_begin();
			$old_file 	= isset($Data['old_file']) ? $Data['old_file'] : '';
			unset($Data['old_file']);
			$result = $this->RegModel->saveData($Data);
			if (isset($_FILES['document']) && $_FILES['document']['name']) {
				$upload = $this->save_upload($result);
				if ($upload) {
					$Return			= array(
						'status'		=> 0,
						'msg'			=> $upload,
					);
					echo json_encode($Return);
					return false;
				}

				if ($old_file) {
					if (file_exists('./directory/REGULATIONS/' . $this->company . "/" . $old_file)) {
						unlink('./directory/REGULATIONS/' . $this->company . "/" . $old_file);
					}
				}
			}
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$Return		= array(
					'status'		=> 0,
					'msg'			=> 'Data Regulation failed to save. Please try again.',
				);
			} else {
				$this->db->trans_commit();
				$Return		= array(
					'status'		=> 1,
					'msg'			=> 'Data Regulation successfully saved..',
					'id'			=> $result,
				);
			}
		} else {
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Data tidak valid..',
			);
		}

		echo json_encode($Return);
	}

	public function save_upload($id)
	{
		if (!is_dir('./directory/REGULATIONS/' . $this->company)) {
			mkdir('./directory/REGULATIONS/' . $this->company, 0755, TRUE);
			chmod("./directory/REGULATIONS/" . $this->company, 'www-data');
		}

		$config['upload_path'] 		= "./directory/REGULATIONS/" . $this->company; //path folder
		$config['allowed_types'] 	= 'pdf'; //type yang dapat diakses bisa anda sesuaikan
		$config['encrypt_name'] 	= true; //Enkripsi nama yang terupload

		$this->upload->initialize($config);
		if ($this->upload->do_upload('document')) :
			$file 					= $this->upload->data();
			$data['document']		= $file['file_name'];
			$data['modified_by']	= $this->auth->user_id();
			$data['modified_at']	= date('Y-m-d H:i:s');
			$this->db->update('regulations', $data, ['id' => $id]);
			return '';
		else :
			$error_msg = $this->upload->display_errors();
			return $error_msg;
		endif;
	}

	public function delete_regulation()
	{
		$id = $this->input->post('id');

		if (($id)) {
			$this->db->trans_begin();
			$this->db->delete('regulations', ['id' => $id]);
			$this->db->delete('regulation_pasal', ['regulation_id' => $id]);
			$this->db->delete('regulation_paragraphs', ['regulation_id' => $id]);
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

	public function delete_pasal()
	{
		$id = $this->input->post('id');
		if (($id)) {
			$this->db->trans_begin();
			$this->RegModel->deletePasal($id);
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
		} else {
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Data not valid..',
			);
		}


		echo json_encode($Return);
	}

	/* del desc */

	public function save_desc()
	{
		$data = $this->input->post();

		if ($data) {
			$this->db->trans_begin();
			$this->RegModel->savePasalDesc($data);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$Return		= array(
					'status'		=> 0,
					'msg'			=> 'Failed to save description, please try again!',
				);
			} else {
				$this->db->trans_commit();
				$Return		= array(
					'status'		=> 1,
					'msg'			=> 'Successfull to save description',
				);
			}
		} else {
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Data not valid, please try again!',
			);
		}

		echo json_encode($Return);
	}

	public function update_desc()
	{
		$data = $this->input->post();

		if ($data) {
			$this->db->trans_begin();
			$this->RegModel->updatePasalDesc($data);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$Return		= array(
					'status'		=> 0,
					'msg'			=> 'Failed to save description, please try again!',
				);
			} else {
				$this->db->trans_commit();
				$Return		= array(
					'status'		=> 1,
					'msg'			=> 'Successfull to save description',
				);
			}
		} else {
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Data not valid, please try again!',
			);
		}

		echo json_encode($Return);
	}

	public function del_desc()
	{
		$id = $this->input->post('id');

		if ($id) {
			$this->db->trans_begin();
			$this->db->delete('regulation_paragraphs', ['id' => $id]);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$Return		= array(
					'status'		=> 0,
					'msg'			=> 'Failed to delete description, please try again!',
				);
			} else {
				$this->db->trans_commit();
				$Return		= array(
					'status'		=> 1,
					'msg'			=> 'Successfull to delete description',
				);
			}
		} else {
			$Return		= array(
				'status'		=> 0,
				'msg'			=> 'Data not valid, please try again!',
			);
		}

		echo json_encode($Return);
	}
}
