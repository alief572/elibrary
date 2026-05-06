<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Mpdf\Mpdf;

class Records extends Admin_Controller
{
	protected $status;
	protected $sts;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('download');
		$this->load->library(array('upload', 'Image_lib'));
		$this->load->model('records/Records_model', 'RecModel');

		$this->template->set('title', 'List Records');
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
		$dataProcedure = $this->RecModel->getActiveProcedures($this->company);
		$this->template->set(['dataProcedure' => $dataProcedure, 'status' => $this->sts]);
		$this->template->render('index');
	}

	public function add()
	{
		$grProcess	= $this->RecModel->getProcedureGroups();
		$users 		= $this->RecModel->getActiveUsers($this->company);
		$jabatan 	= $this->RecModel->getPositions($this->company);

		$this->template->set(['grProcess' => $grProcess, 'users' => $users, 'jabatan' => $jabatan, 'title' => 'Add Procedures']);
		$this->template->render('add');
	}

	public function edit($id = '')
	{
		$Data = $this->RecModel->getProcedureById($id, $this->company);

		if ($Data) {
			$detail 	= $this->RecModel->getProcedureDetails($id);
			$grProcess	= $this->RecModel->getProcedureGroups();
			$getForms	= $this->RecModel->getFormsByProcedure($id);
			$getGuides	= $this->RecModel->getGuidesByProcedure($id);
			$getRecords	= $this->RecModel->getRecordsByProcedure($id);
			$users 		= $this->RecModel->getActiveUsers($this->company);
			$jabatan 	= $this->RecModel->getPositions($this->company);

			$ArrForms = $ArrGuides = [];
			foreach ($getForms as $frm) { $ArrForms[$frm->id] = $frm; }
			foreach ($getGuides as $gui) { $ArrGuides[$gui->id] = $gui; }

			$this->template->set([
				'title' 		=> 'Edit Records', 'data' => $Data, 'users' => $users, 'detail' => $detail,
				'getForms' 		=> $getForms, 'getGuides' => $getGuides, 'getRecords' => $getRecords,
				'jabatan' 		=> $jabatan, 'ArrForms' => $ArrForms, 'ArrGuides' => $ArrGuides, 'sts' => $this->sts,
			]);
			$this->template->render('edit');
		} else {
			$this->template->render('../views/errors/html/error_404_custome', ['heading' => 'Error!', 'message' => 'Data not found..']);
		}
	}

	public function view($id = '', $status = 'PUB')
	{
		$Data 		= $this->RecModel->getProcedureByStatus($id, $this->company, $status);
		$users 		= $this->RecModel->getAllActiveUsers();
		$getForms	= $this->RecModel->getFormsByProcedure($id);
		$getGuides	= $this->RecModel->getGuidesByProcedure($id);
		$jabatan 	= $this->RecModel->getPositions();
		$ArrUsr = $ArrJab = $ArrForms = $ArrGuides = [];

		foreach ($getForms as $frm) { $ArrForms[$frm->id] = $frm; }
		foreach ($getGuides as $gui) { $ArrGuides[$gui->id] = $gui; }
		foreach ($users as $usr) { $ArrUsr[$usr->id_user] = $usr; }
		foreach ($jabatan as $jab) { $ArrJab[$jab->id] = $jab; }

		if ($Data) {
			$detail = $this->RecModel->getProcedureDetails($id);
			$this->template->set([
				'title' => 'Procedures', 'data' => $Data, 'detail' => $detail, 'users' => $users,
				'jabatan' => $jabatan, 'ArrUsr' => $ArrUsr, 'ArrJab' => $ArrJab, 'ArrForms' => $ArrForms, 'ArrGuides' => $ArrGuides,
			]);
			$this->template->render('view');
		} else {
			$this->template->render('../views/errors/html/error_404_custome', ['heading' => 'Error!', 'message' => 'Data not found..']);
		}
	}

	public function save()
	{
		$Data 		= $this->input->post();
		$Data_flow 	= $this->input->post('flow');
		unset($Data['DataTables_Table_0_length'], $Data['DataTables_Table_1_length'], $Data['DataTables_Table_2_length']);

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

			$pro_id = $this->RecModel->saveProcedure($Data, $Data_flow, $this->auth->user_id());
			if ($pro_id) {
				echo json_encode(['status' => 1, 'msg' => 'Data Procedure successfully saved..', 'id' => $pro_id]);
			} else {
				echo json_encode(['status' => 0, 'msg' => 'Data Procedure failed to save. Please try again.']);
			}
		}
	}

	public function saveFlowDetail()
	{
		$Data 	= $this->input->post('flow');
		$pro_id = $this->input->post('procedure_id');
		if ($Data) {
			$success = $this->RecModel->saveFlowDetail($Data, $pro_id, $this->auth->user_id());
			echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Data Flow Detail successfully saved..' : 'Data Flow Detail failed to save.'), 'id' => $pro_id]);
		}
	}

	public function view_form($id = null)
	{
		if ($id) {
			$file 	 = $this->RecModel->getFileById('dir_forms', $id);
			$history = $this->RecModel->getDirectoryLog($id);
			$this->template->set(['sts' => $this->sts, 'file' => $file, 'type' => 'form', 'history' => $history]);
			$this->template->render('show');
		}
	}

	public function upload_form($id = null)
	{
		$users 	 = $this->RecModel->getActiveUsers($this->company);
		$jabatan = $this->RecModel->getPositions();
		$this->template->set(['jabatan' => $jabatan, 'procedure_id' => $id, 'users' => $users, 'type' => "form"]);
		$this->template->render('upload_file_form');
	}

	public function edit_form($id = null)
	{
		$users 	 = $this->RecModel->getActiveUsers($this->company);
		$jabatan = $this->RecModel->getPositions();
		$data 	 = $this->RecModel->getFileById('dir_forms', $id);
		$this->template->set(['data' => $data, 'jabatan' => $jabatan, 'procedure_id' => $data->procedure_id, 'users' => $users, 'type' => "form"]);
		$this->template->render('upload_file_form');
	}

	public function delete_form($id = null)
	{
		if ($id) {
			$fileName = $this->RecModel->getFileName('dir_forms', $id);
			$success = $this->RecModel->deleteFile('dir_forms', $id, $this->auth->user_id());
			if ($success) $this->_delete_file('FORMS', $fileName);
			echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Data successfully deleted.' : 'Data failed to delete.')]);
		}
	}

	public function saveForm()
	{
		$data = $this->input->post('forms');
		if ($data) {
			$id = $data['id'] ?: uniqid(date('m'));
			$data['id'] = $id; $data['name'] = $data['description']; $data['company_id'] = $this->company;
			if (isset($_FILES['forms_image'])) {
				$upload_path = "./directory/FORMS/$this->company/";
				if (!is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);
				$config = ['upload_path' => $upload_path, 'allowed_types' => 'pdf', 'encrypt_name' => true];
				$this->upload->initialize($config);
				if ($this->upload->do_upload('forms_image')) {
					$file = $this->upload->data(); $data['size'] = $file['file_size']; $data['ext'] = $file['file_ext']; $data['file_name'] = $file['file_name'];
					if (isset($data['old_file']) && $data['old_file'] && file_exists($upload_path . $data['old_file'])) unlink($upload_path . $data['old_file']);
				} else {
					echo json_encode(['status' => 0, 'msg' => $this->upload->display_errors()]); return false;
				}
			}
			unset($data['old_file'], $data['type']);
			$success = $this->RecModel->saveFile('dir_forms', $data, $this->auth->user_id());
			if ($success) $this->RecModel->updateHistory(['directory_id' => $id, 'new_status' => ($data['status'] ?? 'OPN'), 'doc_type' => 'Form', 'note' => 'Upload file']);
			echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'File Form successfully saved.' : 'File Form failed to save.')]);
		}
	}

	public function loadDataForm($procedure_id = null)
	{
		$getForms = $this->RecModel->getFormsByProcedure($procedure_id);
		$this->template->set('getForms', $getForms);
		$this->template->render('data-forms');
	}

	public function view_guide($id = null)
	{
		if ($id) {
			$file = $this->RecModel->getFileById('dir_guides', $id);
			$history = $this->RecModel->getDirectoryLog($id);
			$this->template->set(['sts' => $this->sts, 'file' => $file, 'type' => 'guide', 'history' => $history]);
			$this->template->render('show');
		}
	}

	public function upload_guide($id = null)
	{
		$users = $this->RecModel->getActiveUsers($this->company);
		$jabatan = $this->RecModel->getPositions();
		$this->template->set(['jabatan' => $jabatan, 'procedure_id' => $id, 'users' => $users, 'type' => "guide"]);
		$this->template->render('upload_file_guide');
	}

	public function edit_guide($id = null)
	{
		$users = $this->RecModel->getActiveUsers($this->company);
		$jabatan = $this->RecModel->getPositions();
		$data = $this->RecModel->getFileById('dir_guides', $id);
		$this->template->set(['data' => $data, 'jabatan' => $jabatan, 'procedure_id' => $data->procedure_id, 'users' => $users, 'type' => "guide"]);
		$this->template->render('upload_file_guide');
	}

	public function delete_guide($id = null)
	{
		if ($id) {
			$fileName = $this->RecModel->getFileName('dir_guides', $id);
			$success = $this->RecModel->deleteFile('dir_guides', $id, $this->auth->user_id());
			if ($success) $this->_delete_file('GUIDES', $fileName);
			echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Data successfully deleted.' : 'Data failed to delete.')]);
		}
	}

	public function saveGuide()
	{
		$data = $this->input->post('forms');
		if (isset($_FILES['forms_image'])) {
			$upload_path = "./directory/GUIDES/$this->company/";
			if (!is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);
			$config = ['upload_path' => $upload_path, 'allowed_types' => 'pdf', 'encrypt_name' => true];
			$this->upload->initialize($config);
			if ($this->upload->do_upload('forms_image')) {
				$file = $this->upload->data(); $data['size'] = $file['file_size']; $data['ext'] = $file['file_ext']; $data['file_name'] = $file['file_name'];
				if (isset($data['old_file']) && $data['old_file'] && file_exists($upload_path . $data['old_file'])) unlink($upload_path . $data['old_file']);
			} else {
				echo json_encode(['status' => 0, 'msg' => $this->upload->display_errors()]); return false;
			}
		}
		$id = $data['id'] ?: uniqid(date('m'));
		$data['id'] = $id; $data['name'] = $data['description']; $data['company_id'] = $this->company;
		unset($data['old_file'], $data['type']);
		$success = $this->RecModel->saveFile('dir_guides', $data, $this->auth->user_id());
		if ($success) $this->RecModel->updateHistory(['directory_id' => $id, 'new_status' => ($data['status'] ?? 'OPN'), 'doc_type' => 'IK', 'note' => 'Upload file']);
		echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'File IK successfully saved.' : 'File IK failed to save.')]);
	}

	public function loadDataGuide($procedure_id = null)
	{
		$getGuides = $this->RecModel->getGuidesByProcedure($procedure_id);
		$this->template->set('getGuides', $getGuides);
		$this->template->render('data-guides');
	}

	public function view_record($id = null)
	{
		if ($id) {
			$file = $this->RecModel->getFileById('dir_records', $id);
			$history = $this->RecModel->getDirectoryLog($id);
			$this->template->set(['sts' => $this->sts, 'file' => $file, 'type' => 'record', 'history' => $history]);
			$this->template->render('show');
		}
	}

	public function upload_record($id = null, $parent_id = null)
	{
		$users = $this->RecModel->getActiveUsers($this->company);
		$jabatan = $this->RecModel->getPositions();
		$this->template->set(['jabatan' => $jabatan, 'procedure_id' => $id, 'parent_id' => $parent_id, 'users' => $users, 'type' => "record"]);
		$this->template->render('upload_file_record');
	}

	public function edit_record($id = null)
	{
		$users = $this->RecModel->getActiveUsers($this->company);
		$jabatan = $this->RecModel->getPositions();
		$data = $this->RecModel->getFileById('dir_records', $id);
		$this->template->set(['data' => $data, 'jabatan' => $jabatan, 'procedure_id' => $data->procedure_id, 'users' => $users, 'type' => "record"]);
		$this->template->render('upload_file_record');
	}

	public function delete_record($id = null)
	{
		if ($id) {
			$fileName = $this->RecModel->getFileName('dir_records', $id);
			$success = $this->RecModel->deleteFile('dir_records', $id, $this->auth->user_id());
			if ($success) $this->_delete_file('RECORDS', $fileName);
			echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Data successfully deleted.' : 'Data failed to delete.')]);
		}
	}

	public function saveFolder()
	{
		$Data = $this->input->post();
		$Data['id'] = $Data['folder_id']; $Data['name'] = $Data['folder_name']; $Data['parent_id'] = $Data['parent_id'] ?: null;
		unset($Data['folder_id'], $Data['folder_name']);
		$success = $this->RecModel->saveFolder($Data, $this->auth->user_id(), $this->company);
		echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Folder successfully saved.' : 'Folder failed to save.'), 'id' => $Data['procedure_id']]);
	}

	public function records_folder($folder = null, $procedure_id = null)
	{
		if ($folder) $getRecords = $this->RecModel->getSubRecords($procedure_id, $folder);
		$this->template->set(['getRecords' => $getRecords, 'parent_id' => $folder, 'EOF' => false]);
		$this->template->render('data-records');
	}

	public function up_folder($id = null, $procedure_id = null)
	{
		$parent_id = ""; $EOF = true;
		if ($id != 'null') {
			$parent_id = $this->RecModel->getFileById('dir_records', $id)->parent_id;
			if ($parent_id) {
				$getRecords = $this->RecModel->getSubRecords($procedure_id, $parent_id); $EOF = false;
			} else {
				$getRecords = $this->RecModel->getRecordsByProcedure($procedure_id); $EOF = true;
			}
		} else {
			$getRecords = $this->RecModel->getRecordsByProcedure($procedure_id); $EOF = true;
		}
		$this->template->set(['getRecords' => $getRecords, 'parent_id' => $parent_id, 'EOF' => $EOF]);
		$this->template->render('data-records');
	}

	public function refresh($id = '', $procedure_id = null)
	{
		$EOF = true;
		if ($id != 'null') {
			$getRecords = $this->RecModel->getSubRecords($procedure_id, $id); $EOF = false;
		} else {
			$getRecords = $this->RecModel->getRecordsByProcedure($procedure_id); $EOF = true;
		}
		$this->template->set(['getRecords' => $getRecords, 'parent_id' => ($id == 'null' ? '' : $id), 'EOF' => $EOF]);
		$this->template->render('data-records');
	}

	public function saveFileRecord()
	{
		$data = $this->input->post('forms');
		if (isset($_FILES['forms_image'])) {
			$upload_path = "./directory/RECORDS/$this->company/";
			if (!is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);
			$config = ['upload_path' => $upload_path, 'allowed_types' => 'xlsx|xls|pdf', 'encrypt_name' => true];
			$this->upload->initialize($config);
			if ($this->upload->do_upload('forms_image')) {
				$file = $this->upload->data(); $data['size'] = $file['file_size']; $data['ext'] = $file['file_ext']; $data['file_name'] = $file['file_name'];
				if (isset($data['old_file']) && $data['old_file'] && file_exists($upload_path . $data['old_file'])) unlink($upload_path . $data['old_file']);
			} else {
				echo json_encode(['status' => 0, 'msg' => $this->upload->display_errors()]); return false;
			}
		}
		$id = $data['id'] ?: uniqid(date('m'));
		$data['id'] = $id; $data['name'] = $data['description']; $data['company_id'] = $this->company; $data['flag_type'] = 'FILE';
		unset($data['old_file'], $data['type']);
		$success = $this->RecModel->saveFile('dir_records', $data, $this->auth->user_id());
		if ($success) $this->RecModel->updateHistory(['directory_id' => $id, 'new_status' => ($data['status'] ?? 'OPN'), 'doc_type' => 'Record', 'note' => 'Upload file']);
		echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'File Record successfully saved.' : 'File Record failed to save.')]);
	}

	public function delete_procedure($id)
	{
		$success = $this->RecModel->deleteProcedure($id, $this->auth->user_id(), $this->company);
		echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Data Procedure successfully delete..' : 'Data Procedure failed to delete.')]);
	}

	public function review($id)
	{
		$thisData = $this->RecModel->getProcedureById($id, $this->company);
		if(!$thisData->reviewer_id || !$thisData->approval_id){
			echo json_encode(['status' => 0, 'msg' => 'Please select Reviewer User And Approval User first to go to the next process.']); return false;
		}
		$extra = [];
		if($thisData->status == 'RVI'){ $extra['revision'] = $thisData->revision + 1; $extra['revision_date'] = date('Y-m-d H:i:s'); }
		$success = $this->RecModel->updateStatus($id, 'REV', $this->auth->user_id(), $this->company, $extra);
		if ($success) $this->RecModel->updateHistory(['directory_id' => $id, 'old_status' => $thisData->status, 'new_status' => 'REV', 'note' => 'Update data procedure', 'doc_type' => 'Procedure']);
		echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Data Procedure successfully processed for review..' : 'Can\'t process this data.')]);
	}

	public function cancel_review($id)
	{
		$thisData = $this->RecModel->getProcedureById($id, $this->company);
		$success = $this->RecModel->updateStatus($id, 'DFT', $this->auth->user_id(), $this->company);
		if ($success) $this->RecModel->updateHistory(['directory_id' => $id, 'old_status' => $thisData->status, 'new_status' => 'DFT', 'doc_type' => 'Procedure', 'note' => 'Cancel review data procdedure']);
		echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Data Procedure successfully canceled for review..' : 'Can\'t cancel this data.')]);
	}

	public function delete_flow($id)
	{
		$success = $this->RecModel->deleteFlow($id, $this->auth->user_id());
		echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Data Flow successfully deleted..' : 'Data Flow failed to delete.')]);
	}

	public function delete_img($id, $dataImg)
	{
		$success = $this->RecModel->updateStatus($id, null, $this->auth->user_id(), $this->company, ["$dataImg" => null]);
		echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Successfully deleted image..' : 'Failed to delete image..')]);
	}

	public function printOut($id = null)
	{
		$mpdf = new Mpdf(); $mpdf->showImageErrors = true; $mpdf->curlAllowUnsafeSslRequests = true;
		$procedure = $this->RecModel->getProcedureById($id, $this->company);
		$flowDetail = $this->RecModel->getProcedureDetails($id);
		$getForms = $this->RecModel->getFormsByProcedure($id);
		$getGuides = $this->RecModel->getGuidesByProcedure($id);
		$users = $this->RecModel->getActiveUsers($this->company);
		$jabatan = $this->RecModel->getPositions();
		$ArrUsr = $ArrJab = $ArrForms = $ArrGuides = [];
		foreach ($getForms as $frm) { $ArrForms[$frm->id] = $frm; }
		foreach ($users as $usr) { $ArrUsr[$usr->id_user] = $usr; }
		foreach ($jabatan as $jab) { $ArrJab[$jab->id] = $jab; }
		foreach ($getGuides as $gui) { $ArrGuides[$gui->id] = $gui; }
		$Cross = $this->RecModel->getCrossReferences($id, $this->company);
		$ArrData = $ArrStd = [];
		foreach ($Cross as $dt) { $ArrData['id'][$dt->requirement_id] = $dt->requirement_id; $ArrData['standards'][$dt->requirement_id][] = $dt; }
		foreach ($Cross as $dtstd) { $ArrStd[$dtstd->requirement_id] = $dtstd; }
		$allProcedure = $this->RecModel->getActiveProcedures($this->company);
		$company = $this->RecModel->getCompany($this->company);
		$this->template->set(['procedure' => $procedure, 'detail' => $flowDetail, 'ArrUsr' => $ArrUsr, 'ArrJab' => $ArrJab, 'ArrForms' => $ArrForms, 'ArrGuides' => $ArrGuides, 'Data' => $Cross, 'ArrData' => $ArrData, 'ArrStd' => $ArrStd, 'allProcedure' => $allProcedure, 'company_name' => ($company->nm_perusahaan ?? '')]);
		$data = $this->template->load_view('printout'); $mpdf->WriteHTML($data); $mpdf->Output();
	}

	private function _delete_file($dir = null, $fileName = null)
	{
		if ($dir && $fileName && file_exists("./directory/$dir/$this->company/" . $fileName)) unlink("./directory/$dir/$this->company/" . $fileName);
	}

	public function upload_images()
	{
		$this->load->library('upload'); $dataInfo = []; $files = $_FILES;
		$upload_path = './directory/FLOW_IMG/' . $this->company . '/';
		if (!is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);
		$config = ['upload_path' => $upload_path, 'allowed_types' => 'gif|jpg|jpeg|png', 'max_size' => 5120, 'overwrite' => TRUE, 'encrypt_name' => TRUE];
		$cpt = count($_FILES['img_flow']['name']);
		for ($i = 0; $i < $cpt; $i++) {
			$_FILES['img_flow'] = ['name' => $files['img_flow']['name'][$i], 'type' => $files['img_flow']['type'][$i], 'tmp_name' => $files['img_flow']['tmp_name'][$i], 'error' => $files['img_flow']['error'][$i], 'size' => $files['img_flow']['size'][$i]];
			if ($files['img_flow']['name'][$i]) {
				$this->upload->initialize($config); if (!$this->upload->do_upload('img_flow')) return ['error' => 1, 'error_msg' => $this->upload->display_errors()];
				$dataInfo[] = $this->upload->data();
			} else { $dataInfo[$i]['file_name'] = ''; }
		}
		$fileInfo = ['file_name' => ''];
		if (isset($_FILES['flow_file']['name']) && $_FILES['flow_file']['name']) {
			$file_path = './directory/FLOW_FILE/' . $this->company . '/';
			if (!is_dir($file_path)) mkdir($file_path, 0755, TRUE);
			$config['upload_path'] = $file_path; $config['allowed_types'] = 'pdf';
			$this->upload->initialize($config); if (!$this->upload->do_upload('flow_file')) return ['error' => 1, 'error_msg' => $this->upload->display_errors()];
			$fileInfo = $this->upload->data();
		}
		return ['image1' => $dataInfo[0]['file_name'] ?? '', 'image2' => $dataInfo[1]['file_name'] ?? '', 'image3' => $dataInfo[2]['file_name'] ?? '', 'flow_file' => $fileInfo['file_name']];
	}
}
