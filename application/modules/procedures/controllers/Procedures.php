<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Mpdf\Mpdf;

class Procedures extends Admin_Controller
{
	protected $status;
	protected $sts;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('download');
		$this->load->library(array('upload', 'Image_lib'));
		$this->load->model('procedures/Procedures_model', 'ProModel');

		$this->template->set('title', 'List Procedures');
		$this->template->set('icon', 'fa fa-cog');

		date_default_timezone_set("Asia/Bangkok");
		$this->status = [
			'0' => '<span class="badge badge-danger">Invalid</span>',
			'1' => '<span class="badge badge-primary">Publish</span>',
			'DFT' => '<span class="badge badge-secondary">Draft</span>'
		];

		$this->sts = [
			'DFT' => '<span class="label label-secondary label-pill label-inline mr-2">Draft</span>',
			'REV' => '<span class="label label-warning label-pill label-inline mr-2">Waiting Review</span>',
			'COR' => '<span class="label label-danger label-pill label-inline mr-2">Need Correction</span>',
			'APV' => '<span class="label label-info label-pill label-inline mr-2">Waiting Approval</span>',
			'PUB' => '<span class="label label-success label-pill label-inline mr-2">Published</span>',
			'RVI' => '<span class="label label-success label-pill label-inline mr-2">Revision</span>',
			'HLD' => '<span class="label label-light-danger label-pill label-inline mr-2">Hold For Deletion</span>',
		];
	}

	public function index()
	{
		$dataDraft		= $this->ProModel->getProceduresByStatus($this->company, 'DFT');
		$dataRev		= $this->ProModel->getProceduresByStatus($this->company, 'REV');
		$dataCor		= $this->ProModel->getProceduresByStatus($this->company, 'COR');
		$dataApv		= $this->ProModel->getProceduresByStatus($this->company, 'APV');
		$dataPub		= $this->ProModel->getProceduresByStatus($this->company, 'PUB');
		$dataDel		= $this->ProModel->getProceduresByStatus($this->company, 'HLD'); // Adjusted for HLD
		$dataRvi		= $this->ProModel->getProceduresByStatus($this->company, 'RVI');
		$noteRevision	= $this->ProModel->getProcedureLogs('Procedure', 'RVI');
		
		$ArrReason = [];
		foreach (array_reverse($noteRevision) as $rvi) {
			$ArrReason[$rvi->directory_id] = $rvi;
		}

		$groups 		= $this->ProModel->getProcedureGroups();
		$ArrGroup 		= [];
		foreach ($groups as $grp) {
			$ArrGroup[$grp->id] = $grp->name;
		}

		$this->template->set('title', 'List of Procedures');
		$this->template->set([
			'dataDraft' => $dataDraft,
			'dataRev' 	=> $dataRev,
			'dataCor' 	=> $dataCor,
			'dataApv' 	=> $dataApv,
			'dataPub' 	=> $dataPub,
			'dataRvi' 	=> $dataRvi,
			'dataDel' 	=> $dataDel,
			'ArrReason' => $ArrReason,
			'ArrGroup' 	=> $ArrGroup,
		]);
		$this->template->set('status', $this->sts);
		$this->template->render('index');
	}

	public function add()
	{
		$grProcess	= $this->ProModel->getActiveGroups();
		$users 		= $this->ProModel->getActiveUsers($this->company);
		$jabatan 	= $this->ProModel->getPositions($this->company);

		$this->template->set([
			'grProcess' 	=> $grProcess,
			'users' 		=> $users,
			'jabatan' 		=> $jabatan,
		]);

		$this->template->set('title', 'Add Procedures');
		$this->template->render('add');
	}

	public function edit($id = '')
	{
		$Data = $this->ProModel->getProcedureById($id, $this->company);

		if ($Data) {
			$Data_detail 	= $this->ProModel->getProcedureDetails($id);
			$grProcess		= $this->ProModel->getActiveGroups();
			$getForms		= $this->ProModel->getFormsByProcedure($id);
			$getGuides		= $this->ProModel->getGuidesByProcedure($id);
			$getRecords		= $this->ProModel->getRecordsByProcedure($id);
			$users 			= $this->ProModel->getActiveUsers($this->company);
			$jabatan 		= $this->ProModel->getPositions($this->company);

			$ArrForms = [];
			foreach ($getForms as $frm) {
				$ArrForms[$frm->id] = $frm;
			}
			$ArrGuides = [];
			foreach ($getGuides as $gui) {
				$ArrGuides[$gui->id] = $gui;
			}

			$this->template->set([
				'title' 		=> 'Edit Procedures',
				'data' 			=> $Data,
				'users' 		=> $users,
				'detail' 		=> $Data_detail,
				'getForms' 		=> $getForms,
				'getGuides' 	=> $getGuides,
				'getRecords' 	=> $getRecords,
				'jabatan' 		=> $jabatan,
				'ArrForms' 		=> $ArrForms,
				'ArrGuides' 	=> $ArrGuides,
				'sts' 			=> $this->sts,
				'grProcess' 	=> $grProcess,
			]);

			$this->template->render('edit');
		} else {
			$data = ['heading' => 'Error!', 'message' => 'Data not found..'];
			$this->template->render('../views/errors/html/error_404_custome', $data);
		}
	}

	public function view($id = '')
	{
		$Data 		= $this->ProModel->getProcedureById($id, $this->company);
		$users 		= $this->ProModel->getAllActiveUsers();
		$getForms	= $this->ProModel->getFormsByProcedure($id, ''); // Show all
		$getGuides	= $this->ProModel->getGuidesByProcedure($id, '');
		$jabatan 	= $this->ProModel->getPositions();
		$ArrUsr = $ArrJab = $ArrForms = $ArrGuides = [];

		foreach ($getForms as $frm) { $ArrForms[$frm->id] = $frm; }
		foreach ($getGuides as $gui) { $ArrGuides[$gui->id] = $gui; }
		foreach ($users as $usr) { $ArrUsr[$usr->id_user] = $usr; }
		foreach ($jabatan as $jab) { $ArrJab[$jab->id] = $jab; }

		if ($Data) {
			$Data_detail = $this->ProModel->getProcedureDetails($id);
			$this->template->set([
				'title' 		=> 'Procedures',
				'data' 			=> $Data,
				'detail' 		=> $Data_detail,
				'users' 		=> $users,
				'jabatan' 		=> $jabatan,
				'ArrUsr' 		=> $ArrUsr,
				'ArrJab' 		=> $ArrJab,
				'ArrForms' 		=> $ArrForms,
				'ArrGuides' 	=> $ArrGuides,
			]);
			$this->template->render('view');
		} else {
			$data = ['heading' => 'Error!', 'message' => 'Data not found..'];
			$this->template->render('../views/errors/html/error_404_custome', $data);
		}
	}

	public function save()
	{
		$Data 			= $this->input->post();
		$Data_flow 		= $this->input->post('flow');

		if ($Data) {
			if (isset($_FILES)) {
				$images = $this->upload_images();
				if (isset($images['error']) && $images['error'] == '1') {
					echo json_encode(['status' => 0, 'msg' => $images['error_msg'] . ' File gambar gagal diupload, silahkan coba lagi.']);
					return false;
				}
				if (isset($images['image1'])) $Data['image_flow_1'] = $images['image1'];
				if (isset($images['image2'])) $Data['image_flow_2'] = $images['image2'];
				if (isset($images['image3'])) $Data['image_flow_3'] = $images['image3'];
				if (isset($images['flow_file'])) $Data['flow_file'] = $images['flow_file'];
			}

			$Data['company_id'] 	= $this->company;
			if (isset($Data['distribute_id'])) $Data['distribute_id'] = implode(",", $Data['distribute_id']);
			unset($Data['flow']);

			$pro_id = $this->ProModel->saveProcedure($Data, $Data_flow, $this->auth->user_id());
			
			if ($pro_id) {
				echo json_encode(['status' => 1, 'msg' => 'Data Procedure successfully saved..', 'id' => $pro_id]);
			} else {
				echo json_encode(['status' => 0, 'msg' => 'Data Procedure failed to save. Please try again.']);
			}
		}
	}

	public function saveFlowDetail()
	{
		$Data 			= $this->input->post('flow');
		$pro_id 		= $this->input->post('procedure_id');
		if ($Data) {
			$success = $this->ProModel->saveFlowDetail($Data, $pro_id, $this->auth->user_id());
			if ($success) {
				echo json_encode(['status' => 1, 'msg' => 'Data Flow Detail successfully saved..', 'id' => $pro_id]);
			} else {
				echo json_encode(['status' => 0, 'msg' => 'Data Flow Detail failed to save. Please try again.']);
			}
		}
	}

	public function add_flow($id = null)
	{
		$forms 	= $this->ProModel->getActiveFormsByProcedure($id, $this->company);
		$guides = $this->ProModel->getActiveGuidesByProcedure($id, $this->company);

		$this->template->set([
			'procedure_id' 	=>$id,
			'flow' 			=> '',
			'forms' 		=> $forms,
			'guides' 		=> $guides,
		]);
		$this->template->render('form-flow');
	}

	public function edit_flow($proc_id = null, $id = null)
	{
		if ($proc_id && $id) {
			$flow 	= $this->ProModel->getFileById('procedure_details', $id);
			$forms 	= $this->ProModel->getActiveFormsByProcedure($proc_id, $this->company);
			$guides = $this->ProModel->getActiveGuidesByProcedure($proc_id, $this->company);

			$this->template->set([
				'procedure_id' => $proc_id,
				'flow' 		=> $flow,
				'forms' 	=> $forms,
				'guides' 	=> $guides
			]);
			$this->template->render('form-flow');
		}
	}

	public function loadFlow($id)
	{
		if ($id) {
			$Data_detail 	= $this->ProModel->getProcedureDetails($id);
			$getForms		= $this->ProModel->getFormsByProcedure($id);
			$getguides		= $this->ProModel->getGuidesByProcedure($id);
			$ArrForms = $ArrGuides = [];
			foreach ($getForms as $frm) { $ArrForms[$frm->id] = $frm; }
			foreach ($getguides as $gid) { $ArrGuides[$gid->id] = $gid; }

			$this->template->set(['detail' => $Data_detail, 'ArrForms' => $ArrForms, 'ArrGuides' => $ArrGuides]);
			$this->template->render('data-flow');
		}
	}

	public function load_file_flow($id)
	{
		$data = $this->ProModel->getProcedureById($id, $this->company);
		echo json_encode(['status' => ($data ? 1 : 0), 'data' => $data]);
	}

	public function delete_procedure($id)
	{
		$success = $this->ProModel->deleteProcedure($id, $this->auth->user_id(), $this->company);
		echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Data Procedure successfully delete..' : 'Data Procedure failed to delete. Please try again.')]);
	}

	public function review($id)
	{
		$thisData = $this->ProModel->getProcedureById($id, $this->company);
		if(!$thisData->reviewer_id || !$thisData->approval_id){
			echo json_encode(['status' => 0, 'msg' => 'Please select Reviewer User And Approval User first to go to the next process.']);
			return false;
		}

		$extra = [];
		if($thisData->status == 'RVI'){
			$extra['revision'] = $thisData->revision + 1;
			$extra['revision_date'] = date('Y-m-d H:i:s');
		}

		$success = $this->ProModel->updateStatus($id, 'REV', $this->auth->user_id(), $this->company, $extra);
		if ($success) {
			$this->ProModel->updateHistory(['directory_id' => $id, 'old_status' => $thisData->status, 'new_status' => 'REV', 'note' => 'Update data procedure', 'doc_type' => 'Procedure']);
			echo json_encode(['status' => 1, 'msg' => 'Data Procedure successfully processed for review..']);
		} else {
			echo json_encode(['status' => 0, 'msg' => 'Can\'t process this data. Please try again.']);
		}
	}

	public function cancel_review($id)
	{
		$thisData = $this->ProModel->getProcedureById($id, $this->company);
		$success = $this->ProModel->updateStatus($id, 'DFT', $this->auth->user_id(), $this->company);
		if ($success) {
			$this->ProModel->updateHistory(['directory_id' => $id, 'old_status' => $thisData->status, 'new_status' => 'DFT', 'doc_type' => 'Procedure', 'note' => 'Cancel review data procdedure']);
			echo json_encode(['status' => 1, 'msg' => 'Data Procedure successfully canceled for review..']);
		} else {
			echo json_encode(['status' => 0, 'msg' => 'Can\'t cancel this data. Please try again.']);
		}
	}

	public function delete_flow($id)
	{
		$success = $this->ProModel->deleteFlow($id, $this->auth->user_id());
		echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Data Flow successfully deleted..' : 'Data Flow failed to delete. Please try again.')]);
	}

	public function delete_img($id, $dataImg)
	{
		$success = $this->ProModel->updateStatus($id, null, $this->auth->user_id(), $this->company, ["$dataImg" => null]);
		echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Successfully deleted image..' : 'Failed to delete image.. Please try again.')]);
	}

	/* Upload Form Views */

	public function upload_form($procedure_id = null)
	{
		$users = $this->ProModel->getActiveUsers($this->company);
		$this->template->set([
			'procedure_id' => $procedure_id,
			'users'        => $users,
			'data'         => null,
		]);
		$this->template->render('upload_file_form');
	}

	public function upload_guide($procedure_id = null)
	{
		$users = $this->ProModel->getActiveUsers($this->company);
		$this->template->set([
			'procedure_id' => $procedure_id,
			'users'        => $users,
			'data'         => null,
		]);
		$this->template->render('upload_file_guide');
	}

	public function upload_record($procedure_id = null, $parent_id = null)
	{
		$this->template->set([
			'procedure_id' => $procedure_id,
			'parent_id'    => $parent_id,
			'data'         => null,
		]);
		$this->template->render('upload_file_record');
	}

	public function edit_form($id = null)
	{
		$data = $this->ProModel->getFileById('dir_forms', $id);
		$users = $this->ProModel->getActiveUsers($this->company);
		$this->template->set([
			'procedure_id' => $data ? $data->procedure_id : null,
			'users'        => $users,
			'data'         => $data,
		]);
		$this->template->render('upload_file_form');
	}

	public function edit_guide($id = null)
	{
		$data = $this->ProModel->getFileById('dir_guides', $id);
		$users = $this->ProModel->getActiveUsers($this->company);
		$this->template->set([
			'procedure_id' => $data ? $data->procedure_id : null,
			'users'        => $users,
			'data'         => $data,
		]);
		$this->template->render('upload_file_guide');
	}

	/* Generic File Handlers */

	public function saveFileGeneric($type)
	{
		$data = $this->input->post('forms');
		$table = ($type == 'form' ? 'dir_forms' : ($type == 'guide' ? 'dir_guides' : 'dir_records'));
		$dir = strtoupper($type) . 'S';

		if (isset($_FILES['forms_image'])) {
			$upload_path = "./directory/$dir/" . $this->company . "/";
			if (!is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);

			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = 'pdf|xlsx|docx|xls';
			$config['encrypt_name'] = true;
			$this->upload->initialize($config);

			if ($this->upload->do_upload('forms_image')) {
				$file = $this->upload->data();
				$data['file_name'] = $file['file_name'];
				$data['size'] = $file['file_size'];
				$data['ext'] = $file['file_ext'];
				if (isset($data['old_file']) && $data['old_file']) {
					if (file_exists($upload_path . $data['old_file'])) unlink($upload_path . $data['old_file']);
				}
			} else {
				echo json_encode(['status' => 0, 'msg' => $this->upload->display_errors()]);
				return false;
			}
		}

		$data['id'] = $data['id'] ?: uniqid(date('m'));
		$data['name'] = $data['description'];
		$data['company_id'] = $this->company;
		if (isset($data['distribute_id'])) $data['distribute_id'] = implode(",", $data['distribute_id']);
		unset($data['old_file'], $data['type'], $data['flag_type']);

		$success = $this->ProModel->saveFile($table, $data, $this->auth->user_id());
		if ($success) {
			$this->ProModel->updateHistory(['directory_id' => $data['id'], 'new_status' => (isset($data['status']) ? $data['status'] : 'OPN'), 'doc_type' => ucfirst($type), 'note' => 'Upload file']);
			echo json_encode(['status' => 1, 'msg' => "File $type successfully uploaded."]);
		} else {
			echo json_encode(['status' => 0, 'msg' => "File $type failed to upload."]);
		}
	}

	public function view_form($id)
	{
		$this->load->model('documents_list/Documents_list_model', 'List');
		$form 				= $this->List->getFormById($id);
		$history			= $this->List->getHistory($id);
		$users 				= $this->List->getUsers();
		$ArrUsr 			= [];
		foreach ($users as $user) {
			$ArrUsr[$user->id_user] = $user;
		}
		$this->template->set([
			'form' 			=> $form,
			'history' 			=> $history,
			'sts'				=> $this->sts,
			'ArrUsr'			=> $ArrUsr
		]);

		$this->template->render('procedures/view-form');
	}

	public function view_guide($id)
	{
		$this->load->model('documents_list/Documents_list_model', 'List');
		$guide 				= $this->List->getGuideById($id);
		$history			= $this->List->getHistory($id);
		$users 				= $this->List->getUsers();
		$ArrUsr 			= [];
		foreach ($users as $user) {
			$ArrUsr[$user->id_user] = $user;
		}
		$this->template->set([
			'guide' 		=> $guide,
			'history' 		=> $history,
			'sts'			=> $this->sts,
			'ArrUsr'		=> $ArrUsr
		]);

		$this->template->render('procedures/view-guide');
	}

	/* Printout */
	public function printOut($id = null)
	{
		$mpdf = new Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4',
			'autoScriptToLang' => true,
			'autoLangToFont' => true,
		]);
		$mpdf->showImageErrors = false;
		$mpdf->curlAllowUnsafeSslRequests = true;

		$procedure 	= $this->ProModel->getProcedureById($id, $this->company);
		$flowDetail = $this->ProModel->getProcedureDetails($id);
		$getForms	= $this->ProModel->getFormsByProcedure($id);
		$getGuides	= $this->ProModel->getGuidesByProcedure($id);
		$users 		= $this->ProModel->getActiveUsers($this->company);
		$jabatan 	= $this->ProModel->getPositions();

		$ArrUsr = $ArrJab = $ArrForms = $ArrGuides = [];
		foreach ($getForms as $frm) { $ArrForms[$frm->id] = $frm; }
		foreach ($users as $usr) { $ArrUsr[$usr->id_user] = $usr; }
		foreach ($jabatan as $jab) { $ArrJab[$jab->id] = $jab; }
		foreach ($getGuides as $gui) { $ArrGuides[$gui->id] = $gui; }

		$Cross = $this->ProModel->getCrossReferences($id, $this->company);
		$ArrData = $ArrStd = [];
		foreach ($Cross as $dt) {
			$ArrData['id'][$dt->requirement_id] = $dt->requirement_id;
			$ArrData['standards'][$dt->requirement_id][] = $dt;
		}
		foreach ($Cross as $dtstd) { $ArrStd[$dtstd->requirement_id] = $dtstd; }

		$allProcedure = $this->ProModel->getProceduresByStatus($this->company, ''); // Show all active
		$company = $this->ProModel->getCompany($this->company);

		$Data = [
			'procedure' => $procedure, 'detail' => $flowDetail, 'ArrUsr' => $ArrUsr, 'ArrJab' => $ArrJab,
			'ArrForms' => $ArrForms, 'ArrGuides' => $ArrGuides, 'Data' => $Cross, 'ArrData' => $ArrData,
			'ArrStd' => $ArrStd, 'allProcedure' => $allProcedure, 'company_name' => (isset($company->nm_perusahaan) ? $company->nm_perusahaan : ''),
		];

		$this->template->set($Data);
		$data = $this->template->load_view('printout');

		error_reporting(E_ALL & ~E_NOTICE);
		$mpdf->WriteHTML($data);
		if (ob_get_length()) ob_clean();
		$mpdf->Output();
	}

	/* Helpers */
	public function upload_images()
	{
		$this->load->library('upload');
		$dataInfo = []; $files = $_FILES;
		$upload_path = './directory/FLOW_IMG/' . $this->company . '/';
		if (!is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);

		$config = ['upload_path' => $upload_path, 'allowed_types' => 'gif|jpg|jpeg|png', 'max_size' => 5120, 'overwrite' => TRUE, 'encrypt_name' => TRUE];
		$cpt = count($_FILES['img_flow']['name']);

		for ($i = 0; $i < $cpt; $i++) {
			$_FILES['img_flow'] = ['name' => $files['img_flow']['name'][$i], 'type' => $files['img_flow']['type'][$i], 'tmp_name' => $files['img_flow']['tmp_name'][$i], 'error' => $files['img_flow']['error'][$i], 'size' => $files['img_flow']['size'][$i]];
			if ($files['img_flow']['name'][$i]) {
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('img_flow')) return ['error' => 1, 'error_msg' => $this->upload->display_errors()];
				$dataInfo[] = $this->upload->data();
			} else { $dataInfo[$i]['file_name'] = ''; }
		}

		$fileInfo = ['file_name' => ''];
		if (isset($_FILES['flow_file']['name']) && $_FILES['flow_file']['name']) {
			$file_path = './directory/FLOW_FILE/' . $this->company . '/';
			if (!is_dir($file_path)) mkdir($file_path, 0755, TRUE);
			$config['upload_path'] = $file_path; $config['allowed_types'] = 'pdf';
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('flow_file')) return ['error' => 1, 'error_msg' => $this->upload->display_errors()];
			$fileInfo = $this->upload->data();
		}

		return ['image1' => isset($dataInfo[0]['file_name']) ? $dataInfo[0]['file_name'] : '', 'image2' => isset($dataInfo[1]['file_name']) ? $dataInfo[1]['file_name'] : '', 'image3' => isset($dataInfo[2]['file_name']) ? $dataInfo[2]['file_name'] : '', 'flow_file' => $fileInfo['file_name']];
	}
}
