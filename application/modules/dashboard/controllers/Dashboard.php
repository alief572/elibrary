<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
	/*
 * @author Yunaz
 * @copyright Copyright (c) 2016, Yunaz
 * 
 * This is controller for Penerimaan & Dashboard
 */
	public function __construct()
	{
		parent::__construct();

		$this->load->model('dashboard/dashboard_model');
		$this->load->model('dashboard/dashboard_cards_model');
		$this->template->set_theme('dashboard');
		$this->template->page_icon('fa fa-dashboard');

		// $this->cbg = $this->session->app_session['id_cabang'];
	}

	public function index()
	{
		$cards = $this->dashboard_cards_model->get_active_cards();
		$metrics = $this->_get_dashboard_data();

		$this->template->set(
			array_merge([
				'title' => 'Dashboard',
				'cards' => $cards,
				'is_admin' => $this->auth->is_admin(),
			], $metrics)
		);

		$this->template->render('index');
	}

	public function navigation()
	{
		$Data = array();
		if ($this->db->table_exists('directory')) {
			$Data = $this->db->get_where('directory', ['status' => 'OPN'])->result();
		}

		$this->template->set([
			'title' => 'Navigation',
			'Data' => $Data,
			'is_admin' => $this->auth->is_admin(),
		]);

		$this->template->render('index_v1_backup');
	}

	public function classic()
	{
		$this->v1();
	}

	protected function _get_dashboard_data()
	{
		// 1. PROCEDURES
		$procedur_pub = 0;
		$procedur_rev = 0;
		$procedur_cor = 0;
		$procedur_rvi = 0;
		$has_proc_table = true;

		$procedur_pub = $this->db->query("SELECT COUNT(*) as cnt FROM procedures WHERE status = 'PUB' AND company_id = ? AND deleted_at IS NULL", [$this->company])->row()->cnt;
		$procedur_rev = $this->db->query("SELECT COUNT(*) as cnt FROM procedures WHERE status IN ('REV','OPN','APV','DFT') AND company_id = ? AND deleted_at IS NULL", [$this->company])->row()->cnt;
		$procedur_cor = $this->db->query("SELECT COUNT(*) as cnt FROM procedures WHERE status IN ('COR','REJ') AND company_id = ? AND deleted_at IS NULL", [$this->company])->row()->cnt;
		$procedur_rvi = $this->db->query("SELECT COUNT(*) as cnt FROM procedures WHERE status = 'RVI' AND company_id = ? AND deleted_at IS NULL", [$this->company])->row()->cnt;

		// 2. WORK INSTRUCTION (dir_guides / work_instructions)
		$wi_pub = 0;
		$wi_rev = 0;
		$wi_cor = 0;
		$wi_rvi = 0;
		$has_wi_table = $this->db->table_exists('dir_guides') || $this->db->table_exists('work_instructions');

		if ($this->db->table_exists('dir_guides')) {
			$this->db->reset_query();
			$wi_pub = $this->db->where('status', 'PUB')->where('company_id', $this->company)->count_all_results('dir_guides');
			$wi_rev = $this->db->where_in('status', array('REV', 'OPN', 'APV', 'DFT'))->where('company_id', $this->company)->count_all_results('dir_guides');
			$wi_cor = $this->db->where_in('status', array('COR', 'REJ'))->where('company_id', $this->company)->count_all_results('dir_guides');
			$wi_rvi = $this->db->where('status', 'RVI')->where('company_id', $this->company)->count_all_results('dir_guides');
		} elseif ($this->db->table_exists('work_instructions')) {
			$this->db->reset_query();
			$wi_pub = $this->db->where('status', 'PUB')->where('company_id', $this->company)->where('deletion_status !=', 'DEL')->count_all_results('work_instructions');
			$wi_rev = $this->db->where_in('status', array('REV', 'OPN', 'APV', 'DFT'))->where('company_id', $this->company)->where('deletion_status !=', 'DEL')->count_all_results('work_instructions');
			$wi_cor = $this->db->where_in('status', array('COR', 'REJ'))->where('company_id', $this->company)->where('deletion_status !=', 'DEL')->count_all_results('work_instructions');
			$wi_rvi = $this->db->where('status', 'RVI')->where('company_id', $this->company)->where('deletion_status !=', 'DEL')->count_all_results('work_instructions');
		}

		// 3. FORM (dir_forms / forms)
		$form_pub = 0;
		$form_rev = 0;
		$form_cor = 0;
		$form_rvi = 0;
		$has_form_table = $this->db->table_exists('dir_forms') || $this->db->table_exists('forms');

		if ($this->db->table_exists('dir_forms')) {
			$this->db->reset_query();
			$form_pub = $this->db->where('status', 'PUB')->where('company_id', $this->company)->count_all_results('dir_forms');
			$form_rev = $this->db->where_in('status', array('REV', 'OPN', 'APV', 'DFT'))->where('company_id', $this->company)->count_all_results('dir_forms');
			$form_cor = $this->db->where_in('status', array('COR', 'REJ'))->where('company_id', $this->company)->count_all_results('dir_forms');
			$form_rvi = $this->db->where('status', 'RVI')->where('company_id', $this->company)->count_all_results('dir_forms');
		} elseif ($this->db->table_exists('forms')) {
			$this->db->reset_query();
			$form_pub = $this->db->where('status', 'PUB')->where('company_id', $this->company)->where('deletion_status !=', 'DEL')->count_all_results('forms');
			$form_rev = $this->db->where_in('status', array('REV', 'OPN', 'APV', 'DFT'))->where('company_id', $this->company)->where('deletion_status !=', 'DEL')->count_all_results('forms');
			$form_cor = $this->db->where_in('status', array('COR', 'REJ'))->where('company_id', $this->company)->where('deletion_status !=', 'DEL')->count_all_results('forms');
			$form_rvi = $this->db->where('status', 'RVI')->where('company_id', $this->company)->where('deletion_status !=', 'DEL')->count_all_results('forms');
		}

		// 4. RECORDS (dir_records / records: semua file kecuali status DEL)
		$records_total = 0;
		$has_rec_table = $this->db->table_exists('dir_records') || $this->db->table_exists('records');

		if ($this->db->table_exists('dir_records')) {
			$records_total = $this->db->where('status !=', 'DEL')->count_all_results('dir_records');
		} elseif ($this->db->table_exists('records')) {
			$records_total = $this->db->where('deletion_status !=', 'DEL')->count_all_results('records');
		}

		$doc_control = array(
			'procedur' => array(
				'total' => $has_proc_table ? $procedur_pub : 349,
				'pub'   => $has_proc_table ? $procedur_pub : 349,
				'rev'   => $has_proc_table ? $procedur_rev : 40,
				'cor'   => $has_proc_table ? $procedur_cor : 23,
				'rvi'   => $has_proc_table ? $procedur_rvi : 18,
			),
			'wi' => array(
				'total' => $has_wi_table ? ($wi_pub + $wi_rev + $wi_cor + $wi_rvi) : 415,
				'pub'   => $has_wi_table ? $wi_pub : 415,
				'rev'   => $has_wi_table ? $wi_rev : 80,
				'cor'   => $has_wi_table ? $wi_cor : 43,
				'rvi'   => $has_wi_table ? $wi_rvi : 25,
			),
			'form' => array(
				'total' => $has_form_table ? ($form_pub + $form_rev + $form_cor + $form_rvi) : 222,
				'pub'   => $has_form_table ? $form_pub : 222,
				'rev'   => $has_form_table ? $form_rev : 56,
				'cor'   => $has_form_table ? $form_cor : 28,
				'rvi'   => $has_form_table ? $form_rvi : 15,
			),
			'records' => array(
				'total' => $has_rec_table ? $records_total : 0,
				'pub'   => $has_rec_table ? $records_total : 0,
				'rev'   => 0,
				'cor'   => 0,
				'rvi'   => 0,
			),
		);

		// AUDIT & COMPLIANCE
		$car_open = 0;
		$has_ca_table = $this->db->table_exists('corrective_action');
		$has_at_table = $this->db->table_exists('audit_temuan');

		if ($has_ca_table) {
			$car_open = $this->db->query(
				"SELECT COUNT(DISTINCT pa.id) as cnt
				FROM pelaksanaan_audit pa
				INNER JOIN pelaksanaan_audit_temuan pat ON pat.audit_id = pa.id AND pat.status = '1'
				LEFT JOIN corrective_action ca ON ca.pelaksanaan_id = pa.id AND ca.deleted = '0'
				WHERE pa.status = '1'
				AND (ca.id IS NULL OR ca.status_ca NOT IN ('approved', 'closed'))"
			)->row()->cnt;
		} elseif ($has_at_table) {
			$car_open = $this->db->where_in('status', array('OPN', 'OPEN', 'PRO', 'DFT', 'Draft'))->count_all_results('audit_temuan');
		} else {
			$car_open = 0;
		}

		$action_plan_count = 0;
		$has_ap_table = $this->db->table_exists('compliance_opports');
		if ($has_ap_table) {
			if (!empty($this->company)) {
				$this->db->where('company_id', $this->company);
			}
			$action_plan_count = $this->db->count_all_results('compliance_opports');
		} else {
			$action_plan_count = 11;
		}

		// Calculate Compliance to Regulation dynamically from database
		$compliance_rate = 0;
		$total_compliance_items = 0;
		$total_subject_regulations = 0;

		$has_ref_table = $this->db->table_exists('view_references');
		$has_sub_table = $this->db->table_exists('view_compliance_subjects');
		$has_reg_table = $this->db->table_exists('view_ref_regulations');
		$has_rev_table = $this->db->table_exists('compilation_reviews');

		// 1. Get active company reference
		$reference = null;
		if ($has_ref_table) {
			if (!empty($this->company)) {
				$reference = $this->db->get_where('view_references', ['company_id' => $this->company])->row();
			}
			if (!$reference) {
				$reference = $this->db->get('view_references')->row();
			}
		}

		// 2. Count Subject Regulations
		if ($reference && $has_sub_table) {
			$total_subject_regulations = $this->db->where('reference_id', $reference->id)->count_all_results('view_compliance_subjects');
		} elseif ($has_sub_table) {
			$total_subject_regulations = $this->db->count_all_results('view_compliance_subjects');
		} elseif ($this->db->table_exists('compliance_subjects')) {
			$total_subject_regulations = $this->db->count_all_results('compliance_subjects');
		}

		// 3. Get compliance rates - average percentage per subject (same logic as modal)
		if ($has_sub_table && $has_reg_table) {
			// Get subjects first (same as modal)
			if ($reference) {
				$subjects = $this->db->get_where('view_compliance_subjects', ['reference_id' => $reference->id])->result();
			} else {
				$subjects = $this->db->get('view_compliance_subjects')->result();
			}

			if ($subjects && count($subjects) > 0) {
				// Get regulation data grouped by subject
				$this->db->select('subject, SUM(total_compliance) as total_c, SUM(total_not_compliance) as total_nc');
				if ($reference) {
					$this->db->where('reference_id', $reference->id);
				}
				$this->db->group_by('subject');
				$regSummaries = $this->db->get('view_ref_regulations')->result();
				$regMap = [];
				foreach ($regSummaries as $rs) {
					$regMap[$rs->subject] = $rs;
				}

				$sum_pct = 0;
				$sum_c = 0;
				$count_subjects = count($subjects);
				foreach ($subjects as $sub) {
					if (isset($regMap[$sub->id])) {
						$c = intval($regMap[$sub->id]->total_c);
						$nc = intval($regMap[$sub->id]->total_nc);
						$sum_c += $c;
						if (($c + $nc) > 0) {
							$sum_pct += round(($c / ($c + $nc)) * 100);
						}
					}
				}
				$total_compliance_items = $sum_c;
				$total_subject_regulations = $count_subjects;
				if ($count_subjects > 0) {
					$compliance_rate = round($sum_pct / $count_subjects);
				}
			}
		}

		// Fallback sample values ONLY if no tables exist at all
		if (!$has_sub_table && !$has_ref_table && $total_subject_regulations <= 0) {
			$compliance_rate = 85;
			$total_compliance_items = 72;
			$total_subject_regulations = 85;
		}

		// CAR Internal Open (status = draft/reject with overdue deadline)
		$car_internal_open = 0;
		if ($this->db->table_exists('corrective_internal')) {
			$car_internal_open = $this->db->query(
				"SELECT COUNT(*) as cnt FROM corrective_internal WHERE company_id = ? AND deleted_at IS NULL AND status IN ('draft', 'reject')",
				[$this->company]
			)->row()->cnt;
		}

		$audit_compliance = array(
			'car_open' => $car_open,
			'car_internal_open' => $car_internal_open,
			'action_plan' => $action_plan_count,
			'compliance_rate' => $compliance_rate,
			'total_compliance_items' => $total_compliance_items,
			'total_subject_regulations' => $total_subject_regulations,
		);

		// TUGAS SAYA (MY TASKS)
		$tasks = array();
		$session_app = $this->session->userdata('app_session');
		$user_id = null;
		$user_pos = null;
		if (is_object($session_app)) {
			$user_id = isset($session_app->id_user) ? $session_app->id_user : null;
			$user_pos = isset($session_app->id_jabatan) ? $session_app->id_jabatan : null;
		} elseif (is_array($session_app)) {
			$user_id = isset($session_app['id_user']) ? $session_app['id_user'] : null;
			$user_pos = isset($session_app['id_jabatan']) ? $session_app['id_jabatan'] : null;
		}

		if ($user_pos && $this->db->table_exists('procedures')) {
			$proc_tasks = $this->db->select('nomor, name, status')->where('reviewer_id', $user_pos)->where('status', 'OPN')->get('procedures', 3)->result();
			foreach ($proc_tasks as $pt) {
				$tasks[] = array(
					'icon' => 'fa-file-alt',
					'color' => '#f64e60',
					'bg' => 'rgba(246, 78, 96, 0.2)',
					'text' => 'Review dokumen ' . ($pt->nomor ? $pt->nomor : $pt->name),
					'due'  => 'Hari ini'
				);
			}
		}

		if (count($tasks) < 3) {
			$default_tasks = array(
				array(
					'icon' => 'fa-file-alt',
					'color' => '#f64e60',
					'bg' => 'rgba(246, 78, 96, 0.2)',
					'text' => 'Review dokumen SOP-05',
					'due' => 'Hari ini'
				),
				array(
					'icon' => 'fa-check-circle',
					'color' => '#1bc5bd',
					'bg' => 'rgba(27, 197, 189, 0.2)',
					'text' => 'Tindak lanjut CAR-07',
					'due' => 'Besok'
				),
				array(
					'icon' => 'fa-pen',
					'color' => '#3699ff',
					'bg' => 'rgba(54, 153, 255, 0.2)',
					'text' => 'Revisi prosedur PR-14',
					'due' => '3 hari lagi'
				)
			);
			foreach ($default_tasks as $dt) {
				if (count($tasks) >= 3) break;
				$tasks[] = $dt;
			}
		}

		return array(
			'doc_control' => $doc_control,
			'audit_compliance' => array(
				'car_open' => $car_open,
				'car_internal_open' => $car_internal_open,
				'compliance_rate' => $compliance_rate,
				'action_plan' => $action_plan_count,
				'total_compliance_items' => $total_compliance_items,
				'total_subject_regulations' => $total_subject_regulations,
			),
			'my_tasks' => $tasks
		);
	}

	public function get_doc_list()
	{
		$type = $this->input->get('type');
		$status = $this->input->get('status');

		$table = 'view_procedures';
		$redirect_url = base_url('procedures');
		$title = 'Prosedur';

		if ($type === 'wi') {
			$redirect_url = base_url('procedures/guides');
			$title = 'Work Instruction';
		} elseif ($type === 'records') {
			$redirect_url = base_url('records');
			$title = 'Records';
		} elseif ($type === 'form') {
			$redirect_url = base_url('procedures/forms');
			$title = 'Form';
		} elseif ($type === 'car') {
			$redirect_url = base_url('corrective_action');
			$title = 'CAR Internal Audit Open';
		} elseif ($type === 'car_internal') {
			$redirect_url = base_url('corrective_internal');
			$title = 'CAR Internal Open';
		} elseif ($type === 'compliance') {
			$redirect_url = base_url('compliances');
			$title = 'Compliance to Regulation';
		} elseif ($type === 'action_plan') {
			$redirect_url = base_url('action_plan');
			$title = 'Action Plan Compliance';
		}

		$data = array();
		$total_count = 0;
		$table_exists = false;

		// WORK INSTRUCTIONS (dir_guides / view_work_instructions / work_instructions)
		if ($type === 'wi') {
			if ($this->db->table_exists('dir_guides')) {
				$table_exists = true;
				$this->db->from('dir_guides g');
				$this->db->join('procedures p', 'p.id = g.procedure_id', 'left');
				$this->db->join('departements d', 'd.id = p.departement_id', 'left');
				$this->db->where('g.status !=', 'DEL');
				$this->db->where('g.company_id', $this->company);
				if (!empty($status)) {
					if ($status === 'PUB') {
						$this->db->where('g.status', 'PUB');
					} elseif ($status === 'REV') {
						$this->db->where_in('g.status', array('REV', 'OPN', 'APV', 'DFT'));
					} elseif ($status === 'COR') {
						$this->db->where_in('g.status', array('COR', 'REJ'));
					} elseif ($status === 'RVI') {
						$this->db->where('g.status', 'RVI');
					}
				}
				$total_count = $this->db->count_all_results('', FALSE);
				$this->db->select('g.id, g.name, g.number, p.name as procedure_name, d.name as departement_name, g.status');
				$this->db->order_by('g.id', 'DESC')->limit(10);
				$data = $this->db->get()->result();
			} elseif ($this->db->table_exists('view_work_instructions')) {
				$table_exists = true;
				$this->db->where('status !=', 'DEL');
				if (!empty($status)) {
					if ($status === 'PUB') {
						$this->db->where('status', 'PUB');
					} elseif ($status === 'REV') {
						$this->db->where_in('status', array('REV', 'OPN', 'APV', 'DFT'));
					} elseif ($status === 'COR') {
						$this->db->where_in('status', array('COR', 'REJ'));
					} elseif ($status === 'RVI') {
						$this->db->where('status', 'RVI');
					}
				}
				$total_count = $this->db->count_all_results('view_work_instructions', FALSE);
				$this->db->select('id, name, number, procedure_name, departement_name, status');
				$this->db->order_by('id', 'DESC')->limit(10);
				$data = $this->db->get()->result();
			} elseif ($this->db->table_exists('work_instructions')) {
				$table_exists = true;
				$this->db->from('work_instructions wi');
				$this->db->join('procedures p', 'p.id = wi.procedure_id', 'left');
				$this->db->join('departements d', 'd.id = p.departement_id', 'left');
				$this->db->where('wi.deletion_status !=', 'DEL');
				if (!empty($status)) {
					if ($status === 'PUB') {
						$this->db->where('wi.status', 'PUB');
					} elseif ($status === 'REV') {
						$this->db->where_in('wi.status', array('REV', 'OPN', 'APV', 'DFT'));
					} elseif ($status === 'COR') {
						$this->db->where_in('wi.status', array('COR', 'REJ'));
					} elseif ($status === 'RVI') {
						$this->db->where('wi.status', 'RVI');
					}
				}
				$total_count = $this->db->count_all_results('', FALSE);
				$this->db->select('wi.id, wi.name, wi.number, p.name as procedure_name, d.name as departement_name, wi.status');
				$this->db->order_by('wi.id', 'DESC')->limit(10);
				$data = $this->db->get()->result();
			}
		} elseif ($type === 'records' && ($this->db->table_exists('dir_records') || $this->db->table_exists('records'))) {
			$table_exists = true;
			if ($this->db->table_exists('dir_records')) {
				$this->db->from('dir_records r');
				$this->db->join('procedures p', 'p.id = r.procedure_id', 'left');
				$this->db->join('departements d', 'd.id = p.departement_id', 'left');
				$this->db->where('r.status !=', 'DEL');
				if (!empty($status) && $status === 'PUB') {
					$this->db->where('r.status', 'PUB');
				}
				$total_count = $this->db->count_all_results('', FALSE);
				$this->db->select('r.id, r.name, r.number, d.name as departement_name, r.status');
				$this->db->order_by('r.created_at', 'DESC')->limit(10);
				$data = $this->db->get()->result();
			} else {
				$this->db->from('records r');
				$this->db->where('r.deletion_status !=', 'DEL');
				if (!empty($status)) {
					if ($status === 'PUB') {
						$this->db->where_in('r.status', array('PUB', '1'));
					} elseif ($status === 'REV') {
						$this->db->where_in('r.status', array('REV', 'OPN', 'APV', 'DFT'));
					} elseif ($status === 'COR') {
						$this->db->where_in('r.status', array('COR', 'REJ'));
					} elseif ($status === 'RVI') {
						$this->db->where('r.status', 'RVI');
					}
				}
				$total_count = $this->db->count_all_results('', FALSE);
				$this->db->select('r.id, r.name, r.number, r.status');
				$this->db->order_by('r.id', 'DESC')->limit(10);
				$data = $this->db->get()->result();
			}
		} elseif ($type === 'form') {
			if ($this->db->table_exists('dir_forms')) {
				$table_exists = true;
				$this->db->from('dir_forms f');
				$this->db->join('procedures p', 'p.id = f.procedure_id', 'left');
				$this->db->join('departements d', 'd.id = p.departement_id', 'left');
				$this->db->where('f.status !=', 'DEL');
				$this->db->where('f.company_id', $this->company);
				if (!empty($status)) {
					if ($status === 'PUB') {
						$this->db->where('f.status', 'PUB');
					} elseif ($status === 'REV') {
						$this->db->where_in('f.status', array('REV', 'OPN', 'APV', 'DFT'));
					} elseif ($status === 'COR') {
						$this->db->where_in('f.status', array('COR', 'REJ'));
					} elseif ($status === 'RVI') {
						$this->db->where('f.status', 'RVI');
					}
				}
				$total_count = $this->db->count_all_results('', FALSE);
				$this->db->select('f.id, f.name, f.number, f.created_at as effective_date, "00" as revision_number, f.status, d.name as departement_name, p.name as procedure_name');
				$this->db->order_by('f.id', 'DESC')->limit(10);
				$data = $this->db->get()->result();
			} elseif ($this->db->table_exists('forms')) {
				$table_exists = true;
				$this->db->from('forms f');
				$this->db->join('departements d', 'd.id = f.departement_id', 'left');
				$this->db->join('procedures p', 'p.id = f.procedure_id', 'left');
				$this->db->where('f.status !=', 'DEL');
				if (!empty($status)) {
					if ($status === 'PUB') {
						$this->db->where('f.status', 'PUB');
					} elseif ($status === 'REV') {
						$this->db->where_in('f.status', array('REV', 'OPN', 'APV', 'DFT'));
					} elseif ($status === 'COR') {
						$this->db->where_in('f.status', array('COR', 'REJ'));
					} elseif ($status === 'RVI') {
						$this->db->where('f.status', 'RVI');
					}
				}
				$total_count = $this->db->count_all_results('', FALSE);
				$this->db->select('f.id, f.name, f.number, f.effective_date, f.revision_number, f.status, d.name as departement_name, p.name as procedure_name');
				$this->db->order_by('f.id', 'DESC')->limit(10);
				$data = $this->db->get()->result();
			}
		} elseif ($type === 'car_internal') {
			$table_exists = true;
			$redirect_url = base_url('corrective_internal');
			$title = 'CAR Internal Open';
			$data = $this->db->query(
				"SELECT ci.id, ci.nomor_car, ci.tanggal_car, ci.deadline_car, ci.status, d.name as department_name
				FROM corrective_internal ci
				LEFT JOIN departements d ON d.id = ci.department_pic_car_id
				WHERE ci.company_id = ? AND ci.deleted_at IS NULL AND ci.status IN ('draft', 'reject')
				ORDER BY ci.id DESC LIMIT 10",
				[$this->company]
			)->result();
			$total_count = $this->db->query(
				"SELECT COUNT(*) as cnt FROM corrective_internal WHERE company_id = ? AND deleted_at IS NULL AND status IN ('draft', 'reject')",
				[$this->company]
			)->row()->cnt;
		} elseif ($type === 'car') {
			$table_exists = true;
			$redirect_url = base_url('corrective_action');
			$title = 'CAR Internal Audit Open';
			if ($this->db->table_exists('pelaksanaan_audit')) {
				$data = $this->db->query(
					"SELECT pa.id as number,
						COALESCE(p.name, aps.process_name_free) as name,
						COALESCE(ad.department_name, aps.auditee_name_free) as departement_name,
						aps.audit_date as date,
						CASE WHEN ca.id IS NULL THEN 'draft' ELSE ca.status_ca END as status
					FROM pelaksanaan_audit pa
					INNER JOIN pelaksanaan_audit_temuan pat ON pat.audit_id = pa.id AND pat.status = '1'
					LEFT JOIN audit_program_schedule aps ON aps.id = pa.schedule_id
					LEFT JOIN procedures p ON p.id = aps.process_id
					LEFT JOIN audit_program_schedule_auditee apsa ON apsa.schedule_id = aps.id
					LEFT JOIN audit_department ad ON ad.id = apsa.department_id
					LEFT JOIN corrective_action ca ON ca.pelaksanaan_id = pa.id AND ca.deleted = '0'
					WHERE pa.status = '1'
					AND (ca.id IS NULL OR ca.status_ca NOT IN ('approved', 'closed'))
					GROUP BY pa.id
					ORDER BY aps.audit_date DESC
					LIMIT 10"
				)->result();
				$total_count = $this->db->query(
					"SELECT COUNT(DISTINCT pa.id) as cnt
					FROM pelaksanaan_audit pa
					INNER JOIN pelaksanaan_audit_temuan pat ON pat.audit_id = pa.id AND pat.status = '1'
					LEFT JOIN corrective_action ca ON ca.pelaksanaan_id = pa.id AND ca.deleted = '0'
					WHERE pa.status = '1'
					AND (ca.id IS NULL OR ca.status_ca NOT IN ('approved', 'closed'))"
				)->row()->cnt;
			} else {
				$data = array();
				$total_count = 0;
			}
		} elseif ($type === 'action_plan' && $this->db->table_exists('compliance_opports')) {
			$table_exists = true;
			$this->db->from('compliance_opports');
			if (!empty($this->company)) {
				$this->db->where('company_id', $this->company);
			}
			$total_count = $this->db->count_all_results('', FALSE);
			$this->db->select('id as number, description as name, pic, status');
			$this->db->order_by('id', 'DESC')->limit(10);
			$data = $this->db->get()->result();
		} elseif ($type === 'compliance') {
			$reference = null;
			if ($this->db->table_exists('view_references')) {
				if (!empty($this->company)) {
					$reference = $this->db->get_where('view_references', ['company_id' => $this->company])->row();
				}
				if (!$reference) {
					$reference = $this->db->get('view_references')->row();
				}
			}

			if ($this->db->table_exists('view_compliance_subjects')) {
				$table_exists = true;
				if ($reference) {
					$this->db->where('reference_id', $reference->id);
				} elseif (!empty($this->company) && $this->db->field_exists('company_id', 'view_compliance_subjects')) {
					$this->db->where('company_id', $this->company);
				}
				$subjects = $this->db->get('view_compliance_subjects')->result();
				$total_count = count($subjects);

				$regMap = [];
				if ($this->db->table_exists('view_ref_regulations')) {
					$this->db->select('subject, COUNT(id) as total_regs, SUM(total_compliance) as total_c, SUM(total_not_compliance) as total_nc, SUM(total_not_applicable) as total_na');
					if ($reference) {
						$this->db->where('reference_id', $reference->id);
					}
					$this->db->group_by('subject');
					$regSummaries = $this->db->get('view_ref_regulations')->result();
					foreach ($regSummaries as $rs) {
						$regMap[$rs->subject] = $rs;
					}
				}

				foreach ($subjects as $sub) {
					$total_regs = 0;
					$c = 0;
					$nc = 0;
					$na = 0;
					$pct = 0;

					if (isset($regMap[$sub->id])) {
						$rs = $regMap[$sub->id];
						$total_regs = intval($rs->total_regs);
						$c = intval($rs->total_c);
						$nc = intval($rs->total_nc);
						$na = intval($rs->total_na);
						if (($c + $nc) > 0) {
							$pct = round(($c / ($c + $nc)) * 100);
						} elseif ($total_regs > 0 && $c > 0) {
							$pct = round(($c / $total_regs) * 100);
						}
					}

					$data[] = (object) array(
						'number' => 'SUB-' . sprintf('%02d', $sub->id),
						'name' => $sub->name,
						'total_regulations' => $total_regs,
						'compliance' => $c,
						'not_compliance' => $nc,
						'not_applicable' => $na,
						'percentage' => $pct,
						'status' => ($pct >= 100) ? 'CMP' : (($pct > 0) ? 'PRO' : 'OPN')
					);
				}
			} elseif ($this->db->table_exists('compliances')) {
				$table_exists = true;
				$this->db->from('compliances');
				$this->db->where('status !=', 'DEL');
				$total_count = $this->db->count_all_results('', FALSE);
				$this->db->select('id as number, subject as name, 1 as total_regulations, 1 as compliance, 0 as not_compliance, 100 as percentage, status');
				$this->db->order_by('id', 'DESC')->limit(10);
				$data = $this->db->get()->result();
			}
		} elseif ($this->db->table_exists('procedures')) {
			$table_exists = true;
			$this->db->reset_query();
			$this->db->from('procedures p');
			$this->db->join('departements d', 'd.id = p.departement_id', 'left');
			$this->db->where('p.status !=', 'DEL');
			$this->db->where('p.deleted_at IS NULL', NULL, FALSE);
			$this->db->where('p.company_id', $this->company);
			if (!empty($status)) {
				if ($status === 'PUB') {
					$this->db->where('p.status', 'PUB');
				} elseif ($status === 'REV') {
					$this->db->where_in('p.status', array('REV', 'OPN', 'APV', 'DFT'));
				} elseif ($status === 'COR') {
					$this->db->where_in('p.status', array('COR', 'REJ'));
				} elseif ($status === 'RVI') {
					$this->db->where('p.status', 'RVI');
				}
			}
			$total_count = $this->db->count_all_results('', FALSE);
			$this->db->select('p.id, p.nomor as number, p.name, d.name as departement_name, p.group_procedure as group_name, p.status');
			$this->db->order_by('p.id', 'DESC')->limit(10);
			$data = $this->db->get()->result();
		}

		if (!$table_exists && empty($data)) {
			if ($type === 'wi') {
				$all_mock = array(
					'PUB' => array(
						(object) array('number' => 'WI-001', 'name' => 'Instruksi Kerja Pengoperasian Mesin Produksi', 'procedure_name' => 'PR-001 Prosedur Produksi', 'departement_name' => 'Produksi', 'status' => 'PUB'),
						(object) array('number' => 'WI-002', 'name' => 'Instruksi Kerja Pengujian Sample QA', 'procedure_name' => 'PR-002 Prosedur QA', 'departement_name' => 'QA / QC', 'status' => 'PUB'),
						(object) array('number' => 'WI-004', 'name' => 'Instruksi Kerja Kalibrasi Alat Ukur', 'procedure_name' => 'PR-004 Prosedur Kalibrasi', 'departement_name' => 'Maintenance', 'status' => 'PUB'),
					),
					'REV' => array(
						(object) array('number' => 'WI-003', 'name' => 'Instruksi Kerja Pemeliharaan Server IT', 'procedure_name' => 'PR-003 Prosedur IT', 'departement_name' => 'IT Department', 'status' => 'REV'),
						(object) array('number' => 'WI-005', 'name' => 'Instruksi Kerja Penanganan Limbah B3', 'procedure_name' => 'PR-005 Prosedur K3LH', 'departement_name' => 'HSE', 'status' => 'REV'),
					),
					'COR' => array(
						(object) array('number' => 'WI-006', 'name' => 'Instruksi Kerja Penerimaan Barang Gudang', 'procedure_name' => 'PR-006 Prosedur Gudang', 'departement_name' => 'Logistik', 'status' => 'COR'),
					),
					'RVI' => array(
						(object) array('number' => 'WI-007', 'name' => 'Instruksi Kerja Verifikasi Dokumen Ekspor', 'procedure_name' => 'PR-007 Prosedur Ekspor', 'departement_name' => 'Marketing', 'status' => 'RVI'),
					),
				);
				$total_counts = array('PUB' => 415, 'REV' => 80, 'COR' => 43, 'RVI' => 25);
				if (!empty($status) && isset($all_mock[$status])) {
					$data = $all_mock[$status];
					$total_count = isset($total_counts[$status]) ? $total_counts[$status] : count($data);
				} else {
					$data = array_merge($all_mock['PUB'], $all_mock['REV'], $all_mock['COR'], $all_mock['RVI']);
					$total_count = 563;
				}
			} elseif ($type === 'records') {
				$total_count = 185;
				$data = array(
					(object) array('number' => 'REC-001', 'name' => 'Rekaman Hasil Kalibrasi Alat Ukur', 'departement_name' => 'QA / QC', 'status' => 'PUB'),
					(object) array('number' => 'REC-002', 'name' => 'Rekaman Evaluasi Supplier Tahunan', 'departement_name' => 'Purchasing', 'status' => 'PUB'),
					(object) array('number' => 'REC-003', 'name' => 'Rekaman Log Pemeliharaan Server IT', 'departement_name' => 'IT Department', 'status' => 'PUB'),
				);
			} elseif ($type === 'form') {
				$all_mock = array(
					'PUB' => array(
						(object) array('number' => 'FM-001', 'name' => 'Formulir Permintaan Perubahan Dokumen', 'procedure_name' => 'PR-001 Prosedur Dokumen', 'effective_date' => '2026-01-15', 'revision_number' => '01', 'status' => 'PUB'),
						(object) array('number' => 'FM-002', 'name' => 'Formulir Laporan Audit Internal', 'procedure_name' => 'PR-002 Prosedur Audit', 'effective_date' => '2026-02-01', 'revision_number' => '00', 'status' => 'PUB'),
					),
					'REV' => array(
						(object) array('number' => 'FM-004', 'name' => 'Formulir Evaluasi Supplier Berkala', 'procedure_name' => 'PR-004 Prosedur Purchasing', 'effective_date' => '2026-03-01', 'revision_number' => '01', 'status' => 'REV'),
					),
					'COR' => array(
						(object) array('number' => 'FM-003', 'name' => 'Formulir Evaluasi Risk & Opportunity', 'procedure_name' => 'PR-005 Prosedur Compliance', 'effective_date' => '2026-03-10', 'revision_number' => '02', 'status' => 'COR'),
					),
					'RVI' => array(
						(object) array('number' => 'FM-005', 'name' => 'Formulir Serah Terima Barang Masuk', 'procedure_name' => 'PR-006 Prosedur Logistik', 'effective_date' => '2026-03-15', 'revision_number' => '03', 'status' => 'RVI'),
					),
				);
				$total_counts = array('PUB' => 222, 'REV' => 56, 'COR' => 28, 'RVI' => 15);
				if (!empty($status) && isset($all_mock[$status])) {
					$data = $all_mock[$status];
					$total_count = isset($total_counts[$status]) ? $total_counts[$status] : count($data);
				} else {
					$data = array_merge($all_mock['PUB'], $all_mock['REV'], $all_mock['COR'], $all_mock['RVI']);
					$total_count = 321;
				}
			} elseif ($type === 'car') {
				$total_count = 5;
				$data = array(
					(object) array('number' => 'CAR-001', 'name' => 'Ketidaksesuaian Penyimpanan Dokumen QA', 'departement_name' => 'QA / QC', 'date' => '2026-07-20', 'status' => 'OPN'),
					(object) array('number' => 'CAR-002', 'name' => 'Temuan Kalibrasi Alat Ukur Belum Terjadwal', 'departement_name' => 'Maintenance', 'date' => '2026-07-25', 'status' => 'OPN'),
					(object) array('number' => 'CAR-003', 'name' => 'Tindak Lanjut Perbaikan SOP Gudang', 'departement_name' => 'Logistik', 'date' => '2026-08-01', 'status' => 'PRO'),
				);
			} elseif ($type === 'compliance') {
				$total_count = 5;
				$data = array(
					(object) array('number' => 'SUB-01', 'name' => 'K3 (Keselamatan & Kesehatan Kerja)', 'total_regulations' => 18, 'compliance' => 16, 'not_compliance' => 2, 'percentage' => 89, 'status' => 'PRO'),
					(object) array('number' => 'SUB-02', 'name' => 'Lingkungan Hidup (AMDAL / UKL-UPL)', 'total_regulations' => 14, 'compliance' => 13, 'not_compliance' => 1, 'percentage' => 93, 'status' => 'PRO'),
					(object) array('number' => 'SUB-03', 'name' => 'Ketenagakerjaan & Hubungan Industrial', 'total_regulations' => 20, 'compliance' => 18, 'not_compliance' => 2, 'percentage' => 90, 'status' => 'PRO'),
					(object) array('number' => 'SUB-04', 'name' => 'Legalitas Perusahaan & Perizinan Berusaha', 'total_regulations' => 15, 'compliance' => 12, 'not_compliance' => 3, 'percentage' => 80, 'status' => 'PRO'),
					(object) array('number' => 'SUB-05', 'name' => 'Sistem Manajemen Mutu (ISO 9001:2015)', 'total_regulations' => 18, 'compliance' => 13, 'not_compliance' => 5, 'percentage' => 72, 'status' => 'PRO'),
				);
			} elseif ($type === 'action_plan') {
				$total_count = 11;
				$data = array(
					(object) array('number' => 'AP-001', 'name' => 'Rencana Aksi Digitalisasi Pengendalian Form', 'pic' => 'Samsul', 'status' => 'PRO'),
					(object) array('number' => 'AP-002', 'name' => 'Update Matrix Kompetensi Auditor', 'pic' => 'Hikmat', 'status' => 'OPN'),
					(object) array('number' => 'AP-003', 'name' => 'Migrasi Sistem Monitoring Dokumen Askara', 'pic' => 'Admin', 'status' => 'PRO'),
				);
			} else {
				$all_mock = array(
					'PUB' => array(
						(object) array('number' => 'PR-001', 'name' => 'Prosedur Manajemen Dokumen', 'departement_name' => 'QA / QC', 'group_name' => 'SOP Utama', 'status' => 'PUB'),
						(object) array('number' => 'PR-002', 'name' => 'Prosedur Audit Internal', 'departement_name' => 'Internal Audit', 'group_name' => 'SOP Audit', 'status' => 'PUB'),
						(object) array('number' => 'PR-004', 'name' => 'Prosedur Pengendalian Rekaman Mutu', 'departement_name' => 'Document Control', 'group_name' => 'SOP Rekaman', 'status' => 'PUB'),
					),
					'REV' => array(
						(object) array('number' => 'PR-003', 'name' => 'Prosedur Tindakan Perbaikan (CAR)', 'departement_name' => 'Management', 'group_name' => 'SOP Perbaikan', 'status' => 'REV'),
						(object) array('number' => 'PR-007', 'name' => 'Prosedur Penilaian Kinerja Vendor', 'departement_name' => 'Purchasing', 'group_name' => 'SOP Vendor', 'status' => 'REV'),
					),
					'COR' => array(
						(object) array('number' => 'PR-005', 'name' => 'Prosedur Evaluasi Kepatuhan Regulasi', 'departement_name' => 'Compliance', 'group_name' => 'SOP Compliance', 'status' => 'COR'),
					),
					'RVI' => array(
						(object) array('number' => 'PR-006', 'name' => 'Prosedur Manajemen Risiko K3', 'departement_name' => 'HSE', 'group_name' => 'SOP K3', 'status' => 'RVI'),
					),
				);
				$total_counts = array('PUB' => 349, 'REV' => 40, 'COR' => 23, 'RVI' => 18);
				if (!empty($status) && isset($all_mock[$status])) {
					$data = $all_mock[$status];
					$total_count = isset($total_counts[$status]) ? $total_counts[$status] : count($data);
				} else {
					$data = array_merge($all_mock['PUB'], $all_mock['REV'], $all_mock['COR'], $all_mock['RVI']);
					$total_count = 430;
				}
			}
		}

		echo json_encode(array(
			'status' => 1,
			'title' => $title,
			'redirect_url' => $redirect_url,
			'total_count' => $total_count,
			'data' => $data
		));
	}

	public function cards()
	{
		$this->require_administrator();
		$data = $this->dashboard_cards_model->get_all_cards();
		$this->template->set([
			'title' => 'Kelola Card Dashboard',
			'icon' => 'fa fa-th-large',
			'data' => $data,
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
		if (!$this->auth->is_admin()) {
			echo json_encode(['status' => 0, 'msg' => 'Akses ditolak. Hanya administrator.']);
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
			'name' => trim($post['name']),
			'link' => $link,
			'picture' => $picture,
			'sort_order' => (int) (isset($post['sort_order']) ? $post['sort_order'] : 0),
			'is_active' => (isset($post['is_active']) && $post['is_active'] === 'Y') ? 'Y' : 'N',
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
		echo json_encode(['status' => 1, 'msg' => 'Card dashboard berhasil disimpan.']);
	}

	public function delete_card()
	{
		if (!$this->auth->is_admin()) {
			echo json_encode(['status' => 0, 'msg' => 'Akses ditolak. Hanya administrator.']);
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

	protected function require_administrator()
	{
		if (!$this->auth->is_admin()) {
			$this->template->set_message('Akses ditolak. Hanya administrator yang dapat mengelola card dashboard.', 'error');
			redirect('dashboard');
		}
	}

	protected function _render_card_form($data = null)
	{
		$menuData = $this->dashboard_cards_model->get_menu_link_options();
		$storedLink = $data ? $this->_normalize_card_link($data->link) : '';
		$linkInMenu = $storedLink && in_array($storedLink, $menuData['links'], true);

		$this->template->set([
			'data' => $data,
			'menu_options' => $menuData['options'],
			'link_in_menu' => $linkInMenu,
			'stored_link' => $storedLink,
		]);
		$this->template->render('card_form');
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
			'upload_path' => FCPATH . 'assets/images/dashboard/',
			'allowed_types' => 'gif|jpg|jpeg|png|webp',
			'max_size' => 2048,
			'max_width' => 2000,
			'max_height' => 2000,
			'encrypt_name' => true,
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

	public function create_documents()
	{
		$this->template->set('title', 'Create Document');
		$id_jabatan = $this->session->app_session['id_jabatan'];
		$id_user 	= $this->session->app_session['id_user'];
		$doc1 = $this->db->get_where('gambar', ['nama_file !=' => null, 'id_perusahaan' => $this->prsh, 'id_cabang' => $this->cbg])->num_rows();
		$doc2 = $this->db->get_where('gambar1', ['nama_file !=' => null, 'id_perusahaan' => $this->prsh, 'id_cabang' => $this->cbg])->num_rows();
		$doc3 = $this->db->get_where('gambar2', ['nama_file !=' => null, 'id_perusahaan' => $this->prsh, 'id_cabang' => $this->cbg])->num_rows();
		$doc = $doc1 + $doc2 + $doc3;

		// koreksi
		$cor1 = $this->db->get_where('gambar', ['status_approve' => 0, 'prepared_by' => $id_user, 'nama_file !=' => null, 'id_perusahaan' => $this->prsh, 'id_cabang' => $this->cbg])->num_rows();
		$cor2 = $this->db->get_where('gambar1', ['status_approve' => 0, 'prepared_by' => $id_user, 'nama_file !=' => null, 'id_perusahaan' => $this->prsh, 'id_cabang' => $this->cbg])->num_rows();
		$cor3 = $this->db->get_where('gambar2', ['status_approve' => 0, 'prepared_by' => $id_user, 'nama_file !=' => null, 'id_perusahaan' => $this->prsh, 'id_cabang' => $this->cbg])->num_rows();

		// revisi
		$rev1 = $this->db->get_where('gambar', ['status_approve' => 1, 'id_review' => $id_jabatan, 'nama_file !=' => null, 'id_perusahaan' => $this->prsh, 'id_cabang' => $this->cbg])->num_rows();
		$rev2 = $this->db->get_where('gambar1', ['status_approve' => 1, 'id_review' => $id_jabatan, 'nama_file !=' => null, 'id_perusahaan' => $this->prsh, 'id_cabang' => $this->cbg])->num_rows();
		$rev3 = $this->db->get_where('gambar2', ['status_approve' => 1, 'id_review' => $id_jabatan, 'nama_file !=' => null, 'id_perusahaan' => $this->prsh, 'id_cabang' => $this->cbg])->num_rows();

		// approve
		$apv1 = $this->db->get_where('gambar', ['status_approve' => 2, 'id_approval' => $id_jabatan, 'nama_file !=' => null, 'id_perusahaan' => $this->prsh, 'id_cabang' => $this->cbg])->num_rows();
		$apv2 = $this->db->get_where('gambar1', ['status_approve' => 2, 'id_approval' => $id_jabatan, 'nama_file !=' => null, 'id_perusahaan' => $this->prsh, 'id_cabang' => $this->cbg])->num_rows();
		$apv3 = $this->db->get_where('gambar2', ['status_approve' => 2, 'id_approval' => $id_jabatan, 'nama_file !=' => null, 'id_perusahaan' => $this->prsh, 'id_cabang' => $this->cbg])->num_rows();

		$allCorr 	= $cor1 + $cor2 + $cor3;
		$allRev 	= $rev1 + $rev2 + $rev3;
		$allApv 	= $apv1 + $apv2 + $apv3;

		$pictures = $this->db->get('pictures')->result();
		$this->template->set('pictures', $pictures);
		$this->template->set('doc', $doc);
		$this->template->set('docCor', $allCorr);
		$this->template->set('docApv', $allApv);
		$this->template->set('docRev', $allRev);
		$this->template->render('create-document');
	}

	public function picture()
	{
		$id 		= $this->input->post('id');
		$picture 	= $this->db->get_where('pictures', ['id' => $id])->row();

		$this->template->set('picture', $picture);
		$this->template->render('change-picture');
	}

	public function upload()
	{
		$old_picture 	= $this->input->post('old_picture');
		$id 			= $this->input->post('id');

		$config['upload_path']          = './assets/img/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 500;
		$config['max_width']            = 1000;
		$config['max_height']           = 1000;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('picture')) {
			$error = $this->upload->display_errors();

			$collback = [
				'msg' => $error,
				'status' => 0
			];
			echo json_encode($collback);
			return FALSE;
		} else {
			if ($old_picture) {
				unlink('./assets/img/' . $old_picture);
			}
			$dataPicture = $this->upload->data();
			$picture = $dataPicture['file_name'];
		}

		$Arr_data = [
			'pictures' => $picture,
		];
		$this->db->trans_begin();
		$this->db->update('pictures', $Arr_data, ['id' => $id]);

		if ($this->db->trans_status() == false) {
			$this->db->trans_rollback();
			$collback = [
				'msg' => 'Upload Faild, Please ty again!',
				'status' => 0
			];
		} else {
			$this->db->trans_commit();
		}
		$collback = [
			'msg' => 'Upload Success!',
			'status' => 1,
			'picture' => $picture
		];

		echo json_encode($collback);
	}
}
