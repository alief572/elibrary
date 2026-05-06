<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('monitoring/monitoring_model', 'Monitor_model');
		$this->template->set_theme('dashboard');
		$this->template->page_icon('fa fa-dashboard');

		$this->sts = [
			'' => '<span class="label label-light-secondary label-pill label-inline mr-2 text-dark-50">!Null!</span>',
			'OPN' => '<span class="label label-light-primary label-pill label-inline mr-2">New</span>',
			'REV' => '<span class="label label-light-warning label-pill label-inline mr-2">Waiting Review</span>',
			'COR' => '<span class="label label-light-danger label-pill label-inline mr-2">Need Correction</span>',
			'APV' => '<span class="label label-light-info label-pill label-inline mr-2">Waiting Approval</span>',
			'PUB' => '<span class="label label-light-success label-pill label-inline mr-2">Published</span>',
			'RVI' => '<span class="label label-light-danger label-pill label-inline mr-2">Revision</span>',
			'HLD' => '<span class="label label-light-secondary text-secondary label-pill label-inline mr-2">Hold</span>',
		];
	}

	public function index()
	{
		$dtProc = $this->Monitor_model->getProceduresByCompany($this->company);
		$stats = ['rev' => 0, 'cor' => 0, 'pub' => 0, 'apv' => 0, 'rvi' => 0, 'hld' => 0, 'revDel' => 0, 'apvDel' => 0, 'rejDel' => 0];

		foreach ($dtProc as $v) {
			if ($v->status == 'REV') $stats['rev']++;
			elseif ($v->status == 'COR') $stats['cor']++;
			elseif ($v->status == 'PUB') $stats['pub']++;
			elseif ($v->status == 'APV') $stats['apv']++;
			elseif ($v->status == 'RVI') $stats['rvi']++;
			elseif ($v->status == 'HLD') {
				if ($v->deletion_status == 'OPN') $stats['hld']++;
				elseif ($v->deletion_status == 'REV') $stats['revDel']++;
				elseif ($v->deletion_status == 'APV') $stats['apvDel']++;
				elseif ($v->deletion_status == 'REJ') $stats['rejDel']++;
			}
		}

		$Data = $this->Monitor_model->getActiveDirectory($this->company);
		$RecentFiles = $this->Monitor_model->getRecentFiles();

		$this->template->set([
			'title' => 'Dashboard', 'Data' => $Data, 'RecentFiles' => $RecentFiles,
			'dtProcedureRev' => $stats['rev'], 'dtProcedureApv' => $stats['apv'], 'dtProcedureCor' => $stats['cor'],
			'dtProcedureRvi' => $stats['rvi'], 'dtProcedurePub' => $stats['pub'], 'hld' => $stats['hld'],
			'revDel' => $stats['revDel'], 'apvDel' => $stats['apvDel'], 'rejDel' => $stats['rejDel'],
			'dtGuidesApv' => 0, 'dtGuidesRev' => 0, 'dtGuidesCor' => 0, 'dtGuidesPub' => 0, 'dtGuidesRvi' => 0,
		]);
		$this->template->render('index');
	}

	public function view($id = null, $type = null)
	{
		$file = $this->Monitor_model->getProcedureById($id);
		$history = $this->Monitor_model->getDirectoryLogs($id);
		$this->template->set(['sts' => $this->sts, 'file' => $file, 'type' => $type, 'history' => $history, 'view_data' => false]);
		$this->template->render('view');
	}

	public function view_data($id = null, $type = null)
	{
		$file = $this->Monitor_model->getProcedureById($id);
		$history = $this->Monitor_model->getDirectoryLogs($id);
		$this->template->set(['sts' => $this->sts, 'file' => $file, 'type' => $type, 'history' => $history, 'view_data' => true]);
		$this->template->render('view');
	}

	public function review()
	{
		$procedures = $this->Monitor_model->getProceduresByStatus($this->company, 'REV');
		$users = $this->Monitor_model->getAllUsers();
		$positions = $this->Monitor_model->getPositionsByCompany($this->company);
		$ArrPosition = array_combine(array_column($positions, 'id'), array_column($positions, 'name'));
		$ArrUsers = []; foreach ($users as $u) { $ArrUsers[$u->id_user] = $u; }
		$groups = $this->Monitor_model->getGroupProcedures();
		$ArrGroup = array_combine(array_column($groups, 'id'), array_column($groups, 'name'));

		$this->template->set(['title' => 'REVIEW PROCEDURES', 'procedures' => $procedures, 'sts' => $this->sts, 'ArrUsers' => $ArrUsers, 'ArrPosts' => $this->ArrPosts, 'ArrPosition' => $ArrPosition, 'ArrGroup' => $ArrGroup]);
		$this->template->render('list');
	}

	public function load_form_review($id, $type = null)
	{
		$file = $this->Monitor_model->getProcedureById($id);
		$history = $this->Monitor_model->getDirectoryLogs($id);
		$this->template->set(['sts' => $this->sts, 'file' => $file, 'type' => $type, 'history' => $history]);
		$this->template->render('review/review-form');
	}

	public function save_review()
	{
		$data = $this->input->post();
		if ($data) {
			$success = $this->Monitor_model->review($data);
			echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Success process review document...' : 'Failed process review.')]);
		}
	}

	public function correction()
	{
		$procedures = $this->Monitor_model->getProceduresByStatus($this->company, 'COR');
		$users = $this->Monitor_model->getAllUsers();
		$positions = $this->Monitor_model->getPositionsByCompany($this->company);
		$ArrPosition = array_combine(array_column($positions, 'id'), array_column($positions, 'name'));
		$ArrUsers = []; foreach ($users as $u) { $ArrUsers[$u->id_user] = $u; }
		$groups = $this->Monitor_model->getGroupProcedures();
		$ArrGroup = array_combine(array_column($groups, 'id'), array_column($groups, 'name'));

		$this->template->set(['title' => 'CORRECTION PROCEDURES', 'procedures' => $procedures, 'sts' => $this->sts, 'ArrUsers' => $ArrUsers, 'ArrPosition' => $ArrPosition, 'ArrPosts' => $this->ArrPosts, 'ArrGroup' => $ArrGroup]);
		$this->template->render('list');
	}

	public function load_form_correction($id = null, $type = null)
	{
		$file = $this->Monitor_model->getProcedureById($id);
		$history = $this->Monitor_model->getDirectoryLogs($id);
		$this->template->set(['sts' => $this->sts, 'file' => $file, 'type' => $type, 'history' => $history]);
		$this->template->render('correction/correction-form');
	}

	public function save_correction()
	{
		$data = $this->input->post();
		if ($data) {
			$update = ['status' => $data['status'], 'modified_by' => $this->auth->user_id(), 'modified_at' => date('Y-m-d H:i:s')];
			$success = $this->Monitor_model->updateDirectory($data['id'], $update);
			echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Success upload document file...' : 'Failed upload.')]);
		}
	}

	public function approval()
	{
		$procedures = $this->Monitor_model->getProceduresByStatus($this->company, 'APV');
		$users = $this->Monitor_model->getAllUsers();
		$positions = $this->Monitor_model->getPositionsByCompany($this->company);
		$ArrPosition = array_combine(array_column($positions, 'id'), array_column($positions, 'name'));
		$ArrUsers = []; foreach ($users as $u) { $ArrUsers[$u->id_user] = $u; }
		$groups = $this->Monitor_model->getGroupProcedures();
		$ArrGroup = array_combine(array_column($groups, 'id'), array_column($groups, 'name'));

		$this->template->set(['title' => 'APPROVAL PROCEDURES', 'procedures' => $procedures, 'sts' => $this->sts, 'ArrUsers' => $ArrUsers, 'ArrPosts' => $this->ArrPosts, 'ArrPosition' => $ArrPosition, 'ArrGroup' => $ArrGroup]);
		$this->template->render('list');
	}

	public function load_form_approval($id, $type = null)
	{
		$file = ($type == 'procedures' ? $this->Monitor_model->getProcedureById($id) : null);
		$history = $this->Monitor_model->getDirectoryLogs($id);
		$positions = $this->Monitor_model->getPositionsByCompany($this->company);
		$this->template->set(['jabatan' => $positions, 'sts' => $this->sts, 'file' => $file, 'history' => $history, 'type' => $type]);
		$this->template->render('approval/approval-form');
	}

	public function save_approval()
	{
		$data = $this->input->post();
		if ($data) {
			$success = $this->Monitor_model->approval($data);
			echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Success process approval document...' : 'Failed process.')]);
		}
	}

	public function publised()
	{
		$procedures = $this->Monitor_model->getProceduresByStatus($this->company, 'PUB');
		$users = $this->Monitor_model->getAllUsers();
		$ArrUsers = []; foreach ($users as $u) { $ArrUsers[$u->id_user] = $u; }
		$groups = $this->Monitor_model->getGroupProcedures();
		$ArrGroup = array_combine(array_column($groups, 'id'), array_column($groups, 'name'));

		$this->template->set(['title' => 'PUBLISHED PROCEDURES', 'procedures' => $procedures, 'sts' => $this->sts, 'ArrUsers' => $ArrUsers, 'ArrPosts' => $this->ArrPosts, 'ArrGroup' => $ArrGroup]);
		$this->template->render('list');
	}

	public function picture()
	{
		$id = $this->input->post('id');
		$picture = $this->Monitor_model->getPictureById($id);
		$this->template->set('picture', $picture);
		$this->template->render('change-picture');
	}

	public function upload()
	{
		$old_picture = $this->input->post('old_picture'); $id = $this->input->post('id');
		$config = ['upload_path' => './assets/img/', 'allowed_types' => 'gif|jpg|png', 'max_size' => 500, 'max_width' => 1000, 'max_height' => 1000];
		$this->load->library('upload', $config); $this->upload->initialize($config);

		if (!$this->upload->do_upload('picture')) {
			echo json_encode(['msg' => $this->upload->display_errors(), 'status' => 0]); return false;
		} else {
			if ($old_picture && file_exists('./assets/img/' . $old_picture)) unlink('./assets/img/' . $old_picture);
			$dataPicture = $this->upload->data(); $picture = $dataPicture['file_name'];
		}

		$success = $this->Monitor_model->updatePicture($id, ['pictures' => $picture]);
		echo json_encode(['msg' => ($success ? 'Upload Success!' : 'Upload Failed!'), 'status' => ($success ? 1 : 0), 'picture' => $picture]);
	}

	public function revision()
	{
		$procedures = $this->Monitor_model->getProceduresByStatus($this->company, 'RVI');
		$users = $this->Monitor_model->getAllUsers();
		$positions = $this->Monitor_model->getPositionsByCompany($this->company);
		$ArrPosition = array_combine(array_column($positions, 'id'), array_column($positions, 'name'));
		$ArrUsers = []; foreach ($users as $u) { $ArrUsers[$u->id_user] = $u; }
		$groups = $this->Monitor_model->getGroupProcedures();
		$ArrGroup = array_combine(array_column($groups, 'id'), array_column($groups, 'name'));

		$this->template->set(['title' => 'REVISION PROCEDURES', 'procedures' => $procedures, 'sts' => $this->sts, 'ArrUsers' => $ArrUsers, 'ArrPosition' => $ArrPosition, 'ArrPosts' => $this->ArrPosts, 'ArrGroup' => $ArrGroup]);
		$this->template->render('list');
	}

	public function load_form_revision($id, $type = null)
	{
		$file = $this->Monitor_model->getProcedureById($id);
		$history = $this->Monitor_model->getDirectoryLogs($id);
		$this->template->set(['sts' => $this->sts, 'file' => $file, 'type' => $type, 'history' => $history]);
		$this->template->render('revision-form');
	}

	public function save_revision()
	{
		$data = $this->input->post();
		if ($data) {
			$data['status'] = 'RVI';
			$success = $this->Monitor_model->revision($data);
			echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Success revision document file...' : 'Failed revision.')]);
		}
	}

	public function load_form_deletion($id, $type = null)
	{
		$file = $this->Monitor_model->getProcedureById($id);
		$history = $this->Monitor_model->getDirectoryLogs($id);
		$this->template->set(['sts' => $this->sts, 'file' => $file, 'type' => $type, 'history' => $history]);
		$this->template->render('deletion-form');
	}

	public function save_deletion()
	{
		$data = $this->input->post();
		if ($data) {
			$data['status'] = 'HLD';
			$success = $this->Monitor_model->deletion($data);
			echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Success deletion document...' : 'Failed process.')]);
		}
	}

	public function review_deletion()
	{
		$procedures = $this->Monitor_model->getProceduresByStatusAndDeletion($this->company, 'HLD', 'OPN');
		$users = $this->Monitor_model->getAllUsers();
		$groups = $this->Monitor_model->getGroupProcedures();
		$ArrGroup = array_combine(array_column($groups, 'id'), array_column($groups, 'name'));
		$ArrUsers = []; foreach ($users as $u) { $ArrUsers[$u->id_user] = $u; }

		$this->template->set(['title' => 'REVIEW DELETION PROCEDURES', 'procedures' => $procedures, 'sts' => $this->sts, 'ArrUsers' => $ArrUsers, 'ArrPosts' => $this->ArrPosts, 'ArrGroup' => $ArrGroup]);
		$this->template->render('list');
	}

	public function save_rev_deletion()
	{
		$data = $this->input->post();
		if ($data) {
			$data['deletion_status'] = $data['sts']; $data['status'] = 'HLD';
			$data['note'] = ($data['sts'] == 'REV' ? 'Reviewed' : 'Rejected');
			unset($data['sts']);
			$success = $this->Monitor_model->rev_deletion($data);
			echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Success deletion document...' : 'Failed process.')]);
		}
	}

	public function approval_deletion()
	{
		$procedures = $this->Monitor_model->getProceduresByStatusAndDeletion($this->company, 'HLD', 'REV');
		$users = $this->Monitor_model->getAllUsers();
		$groups = $this->Monitor_model->getGroupProcedures();
		$ArrGroup = array_combine(array_column($groups, 'id'), array_column($groups, 'name'));
		$ArrUsers = []; foreach ($users as $u) { $ArrUsers[$u->id_user] = $u; }

		$this->template->set(['title' => 'APPROVAL DELETION PROCEDURES', 'procedures' => $procedures, 'sts' => $this->sts, 'ArrUsers' => $ArrUsers, 'ArrPosts' => $this->ArrPosts, 'ArrGroup' => $ArrGroup]);
		$this->template->render('list');
	}

	public function save_apv_deletion()
	{
		$data = $this->input->post();
		if ($data) {
			$data['deletion_status'] = $data['sts'];
			if ($data['sts'] == 'APV') { $data['status'] = 'DEL'; $data['note'] = 'Approved'; }
			else { $data['status'] = 'HLD'; $data['note'] = 'Rejected'; }
			unset($data['sts']);
			$success = $this->Monitor_model->rev_deletion($data);
			echo json_encode(['status' => ($success ? 1 : 0), 'msg' => ($success ? 'Success deletion document...' : 'Failed process.')]);
		}
	}

	public function deletion_document()
	{
		$procedures = $this->Monitor_model->getProceduresByStatusAndDeletion($this->company, 'HLD', 'APV');
		$users = $this->Monitor_model->getAllUsers();
		$groups = $this->Monitor_model->getGroupProcedures();
		$ArrGroup = array_combine(array_column($groups, 'id'), array_column($groups, 'name'));
		$ArrUsers = []; foreach ($users as $u) { $ArrUsers[$u->id_user] = $u; }

		$this->template->set(['title' => 'NEED ACTION TO DELETE PROCEDURES', 'procedures' => $procedures, 'sts' => $this->sts, 'ArrUsers' => $ArrUsers, 'ArrPosts' => $this->ArrPosts, 'ArrGroup' => $ArrGroup]);
		$this->template->render('list');
	}
}
