<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Documents_list extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('documents_list/Documents_list_model', 'List');
		$this->template->page_icon('fa fa-dashboard');
		$this->MainData 	= $this->List->getMainData();
		$this->sts = [
			'OPN' => '<span class="label label-light-primary label-pill label-inline mr-2">New Upload</span>',
			'REV' => '<span class="label label-light-warning label-pill label-inline mr-2">Waiting Review</span>',
			'COR' => '<span class="label label-light-danger label-pill label-inline mr-2">Need Correction</span>',
			'APV' => '<span class="label label-light-info label-pill label-inline mr-2">Waiting Approval</span>',
			'PUB' => '<span class="label label-light-success label-pill label-inline mr-2">Published</span>',
		];
	}

	public function index()
	{
		redirect('dashboard');
	}

	public function find($id)
	{
		$thisData 		= $this->List->getDirectoryById($id);
		$Data 			= $this->List->getSubFolders($id, $this->company);
		$DataFile 		= $this->List->getSubFiles($id, $this->company);
		$listDataFolder = $this->List->getAllFolders($this->company);
		$listDataFile 	= $this->List->getAllPublishedFiles($this->company);
		$listDataLink 	= $this->List->getAllLinks($this->company);

		$ArrDataFolder = [];
		foreach ($listDataFolder as $listFolder) {
			$ArrDataFolder[$listFolder->parent_id][] = $listFolder;
		}
		$ArrDataFile = [];
		foreach ($listDataFile as $listFile) {
			$ArrDataFile[$listFile->parent_id][] = $listFile;
		}
		$ArrDataLink = [];
		foreach ($listDataLink as $listLink) {
			$ArrDataLink[$listLink->parent_id][] = $listLink;
		}

		$dt 		= $this->List->getDirectoryByIdArray($id);
		$buildBreadcumb = $this->buildBreadcumb($dt);

		$this->template->set('MainData', $this->MainData);
		$this->template->set('company', $this->company);
		$this->template->set('Breadcumb', $buildBreadcumb);
		$this->template->set('thisData', $thisData);
		$this->template->set('Data', $Data);
		$this->template->set('DataFile', $DataFile);
		$this->template->set('ArrDataFolder', $ArrDataFolder);
		$this->template->set('ArrDataFile', $ArrDataFile);
		$this->template->set('ArrDataLink', $ArrDataLink);

		$this->template->render('index');
	}

	function buildBreadcumb($data)
	{
		if (!isset($data['parent_id'])) return '';

		$data = $this->List->getDirectoryById($data['parent_id']);

		if ($data) {
			if ($data->parent_id != '0') {
				$Breadcumb[] =  $data;
			}
			return isset($Breadcumb) ? $Breadcumb : '';
		}
		return '';
	}

	public function show($id)
	{
		$file 		= $this->List->getDirectoryById($id);
		$parent 	= $this->List->getDirectoryById($file->parent_id);
		$dir_name 	= $parent ? $parent->name : '';
		$history	= $this->List->getHistory($id);
		$type 		= 'STANDARDS';

		$this->template->set('type', $type);
		$this->template->set('company', $this->company);
		$this->template->set('dir_name', $dir_name);
		$this->template->set('sts', $this->sts);
		$this->template->set('file', $file);
		$this->template->set('history', $history);
		$this->template->render('show');
	}

	public function procedures($id = null)
	{
		if (isset($id)) {
			$procedure 		= $this->List->getProcedureResult($id);
			$forms 			= $this->List->getFormsByProcedure($id);
			$guides 		= $this->List->getGuidesByProcedure($id);
			$records 		= $this->List->getRecordsFiltered(['company_id' => $this->company, 'procedure_id' => $id, 'parent_id' => null]);
			$countRecords 	= $this->List->countRecords($id, $this->company);

			$this->template->set([
				'procedure' 		=> $procedure,
				'forms' 			=> $forms,
				'guides' 			=> $guides,
				'records' 			=> $records,
				'breadcrumbs'		=> [],
				'id'				=> '',
				'EOF'				=> true,
				'procedure_id'		=> $id,
				'MainData' 			=> $this->MainData,
				'countRecords' 	 	=> $countRecords,
				'user_confidential' => isset($this->user_data->flag_access_confidential) ? $this->user_data->flag_access_confidential : '0',
				'user_group_id' 	=> $this->group_id,
			]);
			$this->template->render('procedures/list-docs');
		} else {
			$groups 		= $this->List->getProcedureGroups();
			$procedures 	= $this->List->getPublishedProcedures($this->company);

			// Show all published procedures (viewPdf generates on-the-fly if PDF doesn't exist)
			$filteredProcedures = $procedures;

			$ArrPro = [];
			foreach ($filteredProcedures as $pro) {
				$ArrPro[$pro['group_procedure']][] = $pro;
			}

			$this->template->set([
				'groups' 		=> $groups,
				'ArrPro' 		=> $ArrPro,
				'MainData' 		=> $this->MainData
			]);
			$this->template->render('procedures/index');
		}
	}

	public function view_procedure($id)
	{
		$this->load->model('procedures/Procedures_model', 'ProModel');

		$Data = $this->ProModel->getProcedureById($id, $this->company);
		if (!$Data) {
			$Data = $this->ProModel->getProcedureById($id);
		}
		if (!$Data) {
			$Data = $this->List->getProcedureById($id);
		}

		if ($Data) {
			$companyId = (isset($Data->company_id) && $Data->company_id) ? $Data->company_id : $this->company;
			$Data_detail = $this->ProModel->getProcedureDetails($id);
			$getForms = $this->ProModel->getFormsByProcedure($id, '');
			$getGuides = $this->ProModel->getGuidesByProcedure($id, '');
			$users = $this->ProModel->getAllActiveUsers();
			$jabatan = $this->ProModel->getPositions();
			$history = $this->ProModel->getDirectoryLogs($id);
			$revisions = $this->ProModel->getProcedureRevisions($id);

			$ArrUsr = $ArrJab = $ArrForms = $ArrGuides = [];
			foreach ($getForms as $frm) {
				$ArrForms[$frm->id] = $frm;
			}
			foreach ($getGuides as $gui) {
				$ArrGuides[$gui->id] = $gui;
			}
			foreach ($users as $usr) {
				$ArrUsr[$usr->id_user] = $usr;
			}
			foreach ($jabatan as $jab) {
				$ArrJab[$jab->id] = $jab;
			}

			$Cross = $this->ProModel->getCrossReferences($id, $companyId);
			$ArrData = $ArrStd = [];
			if ($Cross) {
				foreach ($Cross as $dt) {
					$ArrData['id'][$dt->requirement_id] = $dt->requirement_id;
					$ArrData['standards'][$dt->requirement_id][] = $dt;
				}
				foreach ($Cross as $dtstd) {
					$ArrStd[$dtstd->requirement_id] = $dtstd;
				}
			}

			$company = $this->ProModel->getCompany($companyId);
			$company_name = (isset($company->nm_perusahaan) ? $company->nm_perusahaan : '');

			$this->template->set([
				'title' => 'Procedures',
				'data' => $Data,
				'file' => $Data,
				'docs' => $Data,
				'detail' => $Data_detail,
				'users' => $users,
				'jabatan' => $jabatan,
				'ArrUsr' => $ArrUsr,
				'ArrJab' => $ArrJab,
				'ArrForms' => $ArrForms,
				'ArrGuides' => $ArrGuides,
				'history' => $history,
				'revisions' => $revisions,
				'sts' => $this->sts,
				'Cross' => $Cross,
				'ArrData' => $ArrData,
				'ArrStd' => $ArrStd,
				'company_name' => $company_name,
				'view_data' => true,
			]);

			$this->template->render('procedures/view-docs');
		} else {
			echo '<div class="text-center py-5 text-muted"><h5>Data Prosedur Tidak Ditemukan</h5></div>';
		}
	}

	public function view_record($id)
	{
		$record 			= $this->List->getRecordById($id);
		$history			= $this->List->getHistory($id);
		$users 				= $this->List->getUsers();
		$ArrUsr 			= [];
		foreach ($users as $user) {
			$ArrUsr[$user->id_user] = $user;
		}
		$this->template->set([
			'record' 			=> $record,
			'history' 			=> $history,
			'sts'				=> $this->sts,
			'ArrUsr'			=> $ArrUsr
		]);

		$this->template->render('procedures/view-record');
	}

	public function view_form($id)
	{
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

	/* PROCEDURES */
	/* ========== */

	public function getRecords($methode = null,  $procedure_id = null, $id = null)
	{
		$records = [];
		$EOF = true;
		$breadcrumbs = [];

		if ($methode == 'home' || empty($id) || $id == 'null') {
			$records = $this->List->getRecordsFiltered(['company_id' => $this->company, 'procedure_id' => $procedure_id, 'parent_id' => null]);
			$EOF = true;
			$id = '';
			$breadcrumbs = [];
		} elseif ($methode == 'back') {
			$dir = $this->List->getRecordById($id);
			$parent_id = ($dir && !empty($dir->parent_id) && $dir->parent_id != '0') ? $dir->parent_id : null;
			if (!empty($parent_id)) {
				$records = $this->List->getRecordsFiltered(['company_id' => $this->company, 'procedure_id' => $procedure_id, 'parent_id' => $parent_id]);
				$EOF = false;
				$id = $parent_id;
				$breadcrumbs = $this->List->getRecordBreadcrumbs($parent_id);
			} else {
				$records = $this->List->getRecordsFiltered(['company_id' => $this->company, 'procedure_id' => $procedure_id, 'parent_id' => null]);
				$EOF = true;
				$id = '';
				$breadcrumbs = [];
			}
		} elseif ($methode == 'refresh') {
			if (!empty($id) && $id != 'null') {
				$records = $this->List->getRecordsFiltered(['company_id' => $this->company, 'procedure_id' => $procedure_id, 'parent_id' => $id]);
				$EOF = false;
				$breadcrumbs = $this->List->getRecordBreadcrumbs($id);
			} else {
				$records = $this->List->getRecordsFiltered(['company_id' => $this->company, 'procedure_id' => $procedure_id, 'parent_id' => null]);
				$EOF = true;
				$id = '';
				$breadcrumbs = [];
			}
		} elseif ($methode == 'find') {
			if (!empty($id) && $id != 'null') {
				$records = $this->List->getRecordsFiltered(['company_id' => $this->company, 'procedure_id' => $procedure_id, 'parent_id' => $id]);
				$EOF = false;
				$breadcrumbs = $this->List->getRecordBreadcrumbs($id);
			} else {
				$records = $this->List->getRecordsFiltered(['company_id' => $this->company, 'procedure_id' => $procedure_id, 'parent_id' => null]);
				$EOF = true;
				$id = '';
				$breadcrumbs = [];
			}
		}

		$this->template->set([
			'id' 			=> $id,
			'EOF' 			=> $EOF,
			'procedure_id' 	=> $procedure_id,
			'records' 		=> $records,
			'breadcrumbs'	=> $breadcrumbs,
			'user_confidential' => isset($this->user_data->flag_access_confidential) ? $this->user_data->flag_access_confidential : '0',
			'user_group_id' => $this->group_id,
		]);

		$this->template->render('procedures/records');
	}

	/**
	 * Search records across all folders for a procedure (AJAX)
	 */
	public function search_records()
	{
		$procedure_id = $this->input->post('procedure_id');
		$keyword = $this->input->post('keyword');

		$records = $this->List->searchRecords($procedure_id, $this->company, $keyword);

		$this->template->set([
			'id' 			=> '',
			'EOF' 			=> true,
			'procedure_id' 	=> $procedure_id,
			'records' 		=> $records,
			'breadcrumbs'	=> [],
			'user_confidential' => isset($this->user_data->flag_access_confidential) ? $this->user_data->flag_access_confidential : '0',
			'user_group_id' => $this->group_id,
		]);

		$this->template->render('procedures/records');
	}



	/* PEMENUHAN */

	public function compliances()
	{
		$reference = $this->List->getAllReferences();
		$ArrUsers = [];
		$users       = $this->List->getActiveUsers($this->company);

		foreach ($users as $usr) {
			$ArrUsers[$usr->id_user] = $usr->full_name;
		}

		$this->template->set([
			'reference'     => $reference,
			'ArrUsers'       => $ArrUsers,
		]);
		$this->template->render('compliances/index');
	}

	public function view_compliance($id = null)
	{
		$review = $this->List->getComplianceReview($id);
		$this->template->set([
			'review' 	=> $review,
		]);
		$this->template->render('compliances/view');
	}



	/* MATERI TRAINING */

	public function materi()
	{
		if ($this->input->get('f') && $this->input->get('sub')) {
			$f 				= $this->input->get('f');
			$sub 			= $this->input->get('sub');
			$materi 		= $this->List->getMateriById($f);
			$dtlData 		= $this->List->getMateriData($sub);

			$category 		= [
				'MAT' => 'Materi Training',
				'PRE' => 'Pre Test & Post Test',
				'STU' => 'Studi Kasus, Quiz & Workshop',
				'SIL' => 'Silabus',
				'VID' => 'Video',
				'REF' => 'Reference',
			];
			$ArrDtlData = [];
			foreach ($dtlData as $dtl) {
				$ArrDtlData[$dtl->category][] = $dtl;
			}

			$this->template->set([
				'materi' 		=> $materi,
				'category' 		=> $category,
				'ArrDtlData' 	=> $ArrDtlData,
			]);

			$this->template->render('materi/list');
			return false;
		};


		$materi 		= $this->List->getMateri($this->company);
		$detail 		= $this->List->getMateriDetails($this->company);
		$ArrDetail 		= [];

		foreach ($detail as $dtl) {
			$ArrDetail[$dtl->materi_id][] = $dtl;
		}

		$this->template->set([
			'materi' 		=> $materi,
			'ArrDetail' 	=> $ArrDetail,
		]);

		$this->template->render('materi/index');
	}

	public function show_materi($id = null)
	{
		$file 			= $this->List->getMateriFile($id);
		if (!$file) return;

		$array 			= explode('.', $file->document);
		$extension 		= end($array);

		$this->template->set([
			'file' 		=> $file,
			'ext' 		=> $extension,
			'company' 	=> $this->company,
		]);
		$this->template->render('materi/show');
	}


	/* MASTER IK */

	public function guides()
	{
		if ($this->input->get('f')) {
			$f 					= $this->input->get('f');
			$guide_details 		= $this->List->getGuideDetailByIdIK($f, $this->company);
			$methode 			= ['INS' => 'Insitu', 'LAB' => 'Inlab'];
			$dtlData 			= $this->List->getGuideDetailDataIK($f, $this->company);

			$ArrDtlData = [];
			foreach ($dtlData as $dtl) {
				$ArrDtlData[$dtl->guide_detail_id][] = $dtl;
			}

			$this->template->set([
				'guide_details' => $guide_details,
				'ArrDtlData' 	=> $ArrDtlData,
				'methode' 		=> $methode,
			]);

			$this->template->render('guides/list');
			return false;
		};


		$guides 		= $this->List->getGuidesIK($this->company);
		$details 		= $this->List->getGuideDetailsIK($this->company);
		$ArrDetail 		= [];

		foreach ($details as $dtl) {
			$ArrDetail[$dtl->guide_id][] = $dtl;
		}
		$this->template->set([
			'guides' 		=> $guides,
			'ArrDetail' 	=> $ArrDetail,
		]);

		$this->template->render('guides/index');
	}

	public function view_guides($id = null)
	{
		$data 			= $this->List->getGuideDocuments($id);
		$ArrDoc = [];
		foreach ($data as $d) {
			$ArrDoc[$d->file_type][] = $d;
		}
		$file 			= './directory/MASTER_GUIDES/' . $this->company . '/';
		$methode 		= ['INS' => 'Insitu', 'LAB' => 'Inlab'];
		$this->template->set([
			'data' 		=> $data,
			'ArrDoc'	=> $ArrDoc,
			'file'		=> $file,
			'methode'	=> $methode
		]);

		$this->template->render('guides/view_guides');
	}

	public function view_video($id = null)
	{
		$data 			= $this->List->getGuideVideoData($id);
		$video 			= 'directory/MASTER_GUIDES/video/' . $data->company_id . '/' . $data->video;

		$this->template->set([
			'video'		=> $video,
		]);

		$this->template->render('guides/view_video');
	}

	public function show_file_guides($id = null)
	{
		$file 			= $this->List->getGuideDetailDataByIdIK($id);
		if (!$file) return;

		$array 			= explode('.', $file->document);
		$extension 		= end($array);

		$this->template->set([
			'file' 		=> $file,
			'ext' 		=> $extension
		]);

		$this->template->render('guides/show');
	}

	public function view_file_guides($id = null)
	{
		$file 			= $this->List->getGuideDocumentById($id);
		if (!$file) return;

		$array 			= explode('.', $file->document);
		$extension 		= end($array);

		$this->template->set([
			'file' 		=> $file,
			'ext' 		=> $extension
		]);

		$this->template->render('guides/show');
	}


	/* MANUAL */

	public function manual()
	{
		$res = $this->List->getRecordsFiltered(['description' => 'MANUAL', 'status !=' => 'DEL', 'company_id' => $this->company]);
		$thisData = isset($res[0]) ? $res[0] : null;
		if (!$thisData) {
			redirect('dashboard');
		}

		$Data 			= $this->List->getSubFolders($thisData->id, $this->company);
		$DataFile 		= $this->List->getSubFiles($thisData->id, $this->company);

		$listDataFolder = $this->List->getAllFolders($this->company);
		$listDataFile 	= $this->List->getAllPublishedFiles($this->company);
		$listDataLink 	= $this->List->getAllLinks($this->company);

		$ArrDataFolder = [];
		foreach ($listDataFolder as $listFolder) {
			$ArrDataFolder[$listFolder->parent_id][] = $listFolder;
		}
		$ArrDataFile = [];
		foreach ($listDataFile as $listFile) {
			$ArrDataFile[$listFile->parent_id][] = $listFile;
		}
		$ArrDataLink = [];
		foreach ($listDataLink as $listLink) {
			$ArrDataLink[$listLink->parent_id][] = $listLink;
		}

		$dt 			= $this->List->getDirectoryByIdArray('00062c7fd13bd121');
		$buildBreadcumb = $this->buildBreadcumb($dt);

		$this->template->set('MainData', $this->MainData);
		$this->template->set('company', $this->company);
		$this->template->set('Breadcumb', $buildBreadcumb);
		$this->template->set('thisData', $thisData);
		$this->template->set('Data', $Data);
		$this->template->set('DataFile', $DataFile);
		$this->template->set('ArrDataFolder', $ArrDataFolder);
		$this->template->set('ArrDataFile', $ArrDataFile);
		$this->template->set('ArrDataLink', $ArrDataLink);

		$this->template->render('manual/index');
	}

	public function show_manual($id = null)
	{
		$file 		= $this->List->getDirectoryById($id);
		$parent 	= $this->List->getDirectoryById($file->parent_id);
		$dir_name 	= $parent ? $parent->name : '';
		$history	= $this->List->getHistory($id);
		$type 		= 'MANUAL';

		$this->template->set('type', $type);
		$this->template->set('company', $this->company);
		$this->template->set('dir_name', $dir_name);
		$this->template->set('sts', $this->sts);
		$this->template->set('file', $file);
		$this->template->set('history', $history);
		$this->template->render('show');
	}

	public function find_manual($id)
	{
		$thisData 		= $this->List->getDirectoryById($id);
		$Data 			= $this->List->getSubFolders($id, $this->company);
		$DataFile 		= $this->List->getSubFiles($id, $this->company);
		$listDataFolder = $this->List->getAllFolders($this->company);
		$listDataFile 	= $this->List->getAllPublishedFiles($this->company);
		$listDataLink 	= $this->List->getAllLinks($this->company);

		$ArrDataFolder = [];
		foreach ($listDataFolder as $listFolder) {
			$ArrDataFolder[$listFolder->parent_id][] = $listFolder;
		}
		$ArrDataFile = [];
		foreach ($listDataFile as $listFile) {
			$ArrDataFile[$listFile->parent_id][] = $listFile;
		}
		$ArrDataLink = [];
		foreach ($listDataLink as $listLink) {
			$ArrDataLink[$listLink->parent_id][] = $listLink;
		}

		$dt 			= $this->List->getDirectoryByIdArray($id);
		$buildBreadcumb = $this->buildBreadcumb($dt);

		$this->template->set('MainData', $this->MainData);
		$this->template->set('company', $this->company);
		$this->template->set('Breadcumb', $buildBreadcumb);
		$this->template->set('thisData', $thisData);
		$this->template->set('Data', $Data);
		$this->template->set('DataFile', $DataFile);
		$this->template->set('ArrDataFolder', $ArrDataFolder);
		$this->template->set('ArrDataFile', $ArrDataFile);
		$this->template->set('ArrDataLink', $ArrDataLink);

		$this->template->render('manual/index');
	}


	/* CROSS REFERENCE */

	public function cross()
	{
		$data		= $this->List->getCrossReferences($this->company);
		$this->template->set('data', $data);
		$this->template->set('company_id', $this->company);
		$this->template->render('cross/index');
	}

	public function cross_pasal_proses($id = '')
	{
		$Data 			= $this->List->getCrossReferenceById($id, $this->company);
		$Detail 		= $this->List->getRequirementDetails($Data->standard_id);
		$DetailCross	= $this->List->getCrossReferenceDetails($id);
		$Procedure		= array_combine(array_column($DetailCross, 'chapter_id'), array_column($DetailCross, 'procedure_id'));
		$other_docs 	= array_combine(array_column($DetailCross, 'chapter_id'), array_column($DetailCross, 'other_docs'));

		$procedures 	= $this->List->getRecordsFiltered(['company_id' => $this->company, 'status !=' => 'DEL']);
		$list_procedure = [];
		foreach ($procedures as $pro) {
			$list_procedure[$pro->id] = $pro->name;
		}

		$this->template->set([
			'Data' 				=> $Data,
			'Detail' 			=> $Detail,
			'list_procedure' 	=> $list_procedure,
			'other_docs' 		=> $other_docs,
			'Procedure' 		=> $Procedure,
		]);


		$this->template->render('cross/view');
	}

	public function cross_process_pasal($id = '')
	{
		$requirement = $this->List->getRequirementById($id, $this->company);
		$procedures  = $this->List->getRecordsFiltered(['company_id' => $this->company, 'status !=' => 'DEL']);

		$Data = [];
		foreach ($procedures as $pr) {
			$Data[$pr->id] = $this->List->getCrossReferenceByProcedureAndRequirement($pr->id, $id, $this->company);
		}

		$this->template->set([
			'Data' 			=> $Data,
			'requirement' 	=> $requirement,
			'procedures' 	=> $procedures,
		]);

		$this->template->render('cross/process_to_pasal');
	}


	public function all_cross()
	{
		$requirement  = $this->List->getCrossReferences($this->company);
		$procedures   = $this->List->getRecordsFiltered(['company_id' => $this->company, 'status !=' => 'DEL']);

		$Data = [];
		foreach ($requirement as $req) {
			foreach ($procedures as $pr) {
				$Data[$req->standard_id][$pr->id] = $this->List->getCrossReferenceByProcedureAndRequirement($pr->id, $req->standard_id, $this->company);
			}
		}

		$ArrPro = [];
		foreach ($procedures as $p) {
			$ArrPro[$p->id] = $p->name;
		}

		$this->template->set([
			'Data' 			=> $Data,
			'requirement' 	=> $requirement,
			'ArrPro' 		=> $ArrPro,
			'procedures' 	=> $procedures,
		]);

		$this->template->render('cross/all-cross');
	}
}
