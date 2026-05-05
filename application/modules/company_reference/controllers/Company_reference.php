<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * @author Syamsudin
 * @copyright Copyright (c) 2021, Syamsudin
 *
 * Controller untuk Company Reference.
 * Tanggung jawab: menerima input HTTP, mendelegasikan ke model,
 * dan merender view atau mengembalikan JSON response.
 * Tidak ada logika query database di sini (SRP).
 */

class Company_reference extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('download');
		$this->load->library(array('upload', 'Image_lib'));
		$this->load->model('company_reference/Company_reference_model', 'ReferenceModel');
		$this->load->model('company_reference/Compliance_model', 'ComplianceModel');

		$this->template->set('title', 'Company Reference');
		$this->template->set('icon', 'fa fa-building');

		date_default_timezone_set("Asia/Bangkok");
	}

	public function index()
	{
		$data = $this->ReferenceModel->getOpenByCompany($this->company);
		$done = $this->ReferenceModel->getDoneAll();
		$refIds = array_map(function ($d) {
			return $d->id; }, $data);

		$this->template->set([
			'data' => $data,
			'done' => $done,
			'ArrStd' => $this->ReferenceModel->getStandardsGrouped($refIds),
			'ArrReg' => $this->ReferenceModel->getRegulationsGrouped($refIds),
		]);

		$this->template->render('index');
	}

	public function add()
	{
		$this->template->set([
			'title' => 'Add Company Reference',
			'Companies' => $this->ReferenceModel->getCompanyById($this->company),
			'branch' => $this->ReferenceModel->getBranchesByCompany($this->company),
		]);

		$this->template->render('add');
	}

	public function edit($id = '', $branch = null)
	{
		$Data = $this->ReferenceModel->findOpenById($id, $branch);

		if ($Data) {
			$dataReg = $this->ReferenceModel->getRegsByRef($id, $branch);

			$ArrReg = [];
			foreach ($dataReg as $reg) {
				$ArrReg[$reg->subject][] = $reg;
			}

			$this->template->set([
				'title' => 'Edit Company Reference',
				'Data' => $Data,
				'datStd' => $this->ReferenceModel->getStandardsByRef($id),
				'dataReg' => $dataReg,
				'Companies' => $this->ReferenceModel->getAllCompanies(),
				'standards' => $this->ReferenceModel->getActiveRequirements(),
				'subjects' => $this->ReferenceModel->getSubjects($this->company, $branch),
				'ArrReg' => $ArrReg,
				'ArrRegulation' => json_encode($this->ReferenceModel->getRegulationsForDropdown()),
			]);
			$this->template->render('edit');
		} else {
			$this->template->render('../views/errors/html/error_404_custome', [
				'heading' => 'Error!',
				'message' => 'Data not found..',
			]);
		}
	}

	public function view($id = null, $branch = null)
	{
		if (!$id)
			return;

		$Data = $this->ReferenceModel->findOpenByIdOnly($id);

		if ($Data) {
			$this->template->set([
				'title' => 'View Company Reference',
				'Data' => $Data,
				'datStd' => $this->ReferenceModel->getStandardsByRef($id),
				'dataReg' => $this->ReferenceModel->getRegsByRefOnly($id),
				'Companies' => $this->ReferenceModel->getAllCompanies(),
				'standards' => $this->ReferenceModel->getActiveRequirements(),
				'subjects' => $this->ReferenceModel->getSubjects($this->company, $branch),
				'regulations' => $this->ReferenceModel->getPublishedRegulationsAll(),
			]);
			$this->template->render('view');
		} else {
			$this->template->render('../views/errors/html/error_404_custome', [
				'heading' => 'Error!',
				'message' => 'Data not found..',
			]);
		}
	}

	public function save()
	{
		$Data = $this->input->post();

		if (!$Data) {
			echo json_encode(['status' => 0, 'msg' => 'Data not valid, please try again!']);
			return;
		}

		$this->db->trans_begin();
		$saved = $this->ReferenceModel->saveData($Data);

		if (isset($saved['id'])) {
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$Return = ['status' => 0, 'msg' => 'Data Chapter failed to save. Please try again.'];
			} else {
				$this->db->trans_commit();
				$Return = ['status' => 1, 'msg' => 'Data Chapter successfully saved..', 'id' => $saved['id']];
			}
		} else {
			$this->db->trans_rollback();
			$Return = ['status' => 0, 'msg' => 'Company name has already been created'];
		}

		echo json_encode($Return);
	}

	public function delete()
	{
		$id = $this->input->post('id');

		if (!$id) {
			echo json_encode(['status' => 0, 'msg' => 'Data not valid. Please try again.']);
			return;
		}

		echo json_encode($this->ReferenceModel->deleteReference($id));
	}

	public function delete_reg()
	{
		$id = $this->input->post('id');

		if (!$id) {
			echo json_encode(['status' => 0, 'msg' => 'Data not valid. Please try again.']);
			return;
		}

		echo json_encode($this->ReferenceModel->deleteRegulation($id));
	}

	/* New Compliance */
	public function select_subjects($branch = null, $reference_id)
	{
		$this->template->render('select_subjects', [
			'subjects' => $this->ComplianceModel->getAllSubjects(),
			'exist_subjects' => $this->ComplianceModel->getExistingSubjects($this->company, $reference_id),
			'branch' => $branch,
			'reference_id' => $reference_id,
		]);
	}

	public function save_subject()
	{
		try {
			$post = $this->input->post();

			if (empty($post['subject_id'])) {
				echo json_encode(['status' => 0, 'msg' => 'Data not valid, please try again!']);
				return;
			}
			$Return = $this->ComplianceModel->saveSubject(
				$post['subject_id'],
				$this->company,
				$this->auth->user_id(),
				$post['reference_id'],
				isset($post['branch_id']) ? $post['branch_id'] : null
			);
		} catch (\Exception $th) {
			$Return = ['status' => 0, 'msg' => $th->getMessage()];
		}

		echo json_encode($Return);
	}
}
