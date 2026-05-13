<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Audit_checklist extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('audit_checklist/Audit_checklist_model', 'AuditModel');
        $this->template->set([
            'title' => 'Audit Checklist',
            'icon' => 'fa fa-check-double'
        ]);

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $data = $this->AuditModel->getActiveChecklists();
        $this->template->set('data', $data);
        $this->template->render('index');
    }

    public function add()
    {
        $data = $this->AuditModel->getProcedures($this->company);
        $this->template->set('title', 'Add New Checklist');
        $this->template->set('data', $data);
        $this->template->render('add');
    }

    public function select_procedure($id = '')
    {
        $Data = $this->AuditModel->getCrossReferences($id, $this->company);

        $ArrData = [];
        foreach ($Data as $dt) {
            $ArrData['id'][$dt->requirement_id] = $dt->requirement_id;
            $ArrData['standards'][$dt->requirement_id][] = $dt;
        }
        $ArrStd = [];
        foreach ($Data as $dtstd) {
            $ArrStd[$dtstd->requirement_id] = $dtstd;
        }

        $procedure = $this->AuditModel->getProcedures($this->company);

        $this->template->set([
            'Data'             => $Data,
            'ArrData'         => $ArrData,
            'ArrStd'         => $ArrStd,
            'procedure'     => $procedure,
        ]);

        $this->template->render('load_proses');
    }

    public function view_pasal($id = '')
    {
        $Data = $this->AuditModel->getRequirementDetailById($id);
        echo json_encode($Data);
    }

    public function edit($id)
    {
        $data        = $this->AuditModel->getChecklistById($id);
        $procedures  = $this->AuditModel->getProcedures($this->company);
        $Cross       = $this->AuditModel->getCrossReferences($data->procedure_id, $this->company);

        $ArrData = [];
        foreach ($Cross as $dt) {
            $ArrData['id'][$dt->requirement_id] = $dt->requirement_id;
            $ArrData['standards'][$dt->requirement_id][] = $dt;
        }
        $ArrStd = [];
        foreach ($Cross as $dtstd) {
            $ArrStd[$dtstd->requirement_id] = $dtstd;
        }

        $checklist = $this->AuditModel->getChecklistDetails($id);

        $this->template->set([
            'data'       => $data,
            'Cross'      => $Cross,
            'ArrData'    => $ArrData,
            'ArrStd'     => $ArrStd,
            'procedures' => $procedures,
            'checklist'  => $checklist,
        ]);

        $this->template->render('edit');
    }

    public function view($id)
    {
        $data        = $this->AuditModel->getChecklistById($id);
        $procedures  = $this->AuditModel->getProcedures($this->company);
        $Cross       = $this->AuditModel->getCrossReferences($data->procedure_id, $this->company);

        $ArrData = [];
        foreach ($Cross as $dt) {
            $ArrData['id'][$dt->requirement_id] = $dt->requirement_id;
            $ArrData['standards'][$dt->requirement_id][] = $dt;
        }
        $ArrStd = [];
        foreach ($Cross as $dtstd) {
            $ArrStd[$dtstd->requirement_id] = $dtstd;
        }

        $checklist = $this->AuditModel->getChecklistDetails($id);

        $this->template->set([
            'data'       => $data,
            'Cross'      => $Cross,
            'ArrData'    => $ArrData,
            'ArrStd'     => $ArrStd,
            'procedures' => $procedures,
            'checklist'  => $checklist,
        ]);

        $this->template->render('view_checklist');
    }

    public function save()
    {
        $data       = $this->input->post();
        $checklist  = isset($data['checklist']) ? $data['checklist'] : [];
        unset($data['checklist']);

        if ($data) {
            $success = $this->AuditModel->saveChecklist($data, $checklist, $this->auth->user_id());
            if ($success) {
                $return = array('status' => 1, 'msg' => 'Data has successfull saved. Thanks you.');
            } else {
                $return = array('status' => 0, 'msg' => 'Data has Failed save. Please Try Again!');
            }
        } else {
            $return = array('status' => 0, 'msg' => 'Data not valid. Please Try Again!');
        }
        echo json_encode($return);
    }

    function delete()
    {
        $id = $this->input->post('id');
        if ($id) {
            $success = $this->AuditModel->deleteChecklist($id);
            if ($success) {
                $Return = ['msg' => "Successfull delete data.", 'status' => 1];
            } else {
                $Return = ['msg' => "Failed deleting data, please try again.", 'status' => 0];
            }
        } else {
            $Return = ['msg' => "Data not valid", 'status' => 0];
        }
        echo json_encode($Return);
    }

    function delete_checklist()
    {
        $id = $this->input->post('id');
        if ($id) {
            $success = $this->AuditModel->deleteChecklistDetail($id);
            if ($success) {
                $Return = ['msg' => "Successfull delete data.", 'status' => 1];
            } else {
                $Return = ['msg' => "Failed deleting data, please try again.", 'status' => 0];
            }
        } else {
            $Return = ['msg' => "Data not valid", 'status' => 0];
        }
        echo json_encode($Return);
    }

    function audit($id)
    {
        if ($id) {
            $cklst      = $this->AuditModel->getChecklistByViewId($id);
            $users      = $this->AuditModel->getUsers($this->company);
            $procedures = $this->AuditModel->getProcedures($this->company);
            $Cross      = $this->AuditModel->getCrossReferences($cklst->procedure_id, $this->company);

            $ArrData = [];
            foreach ($Cross as $dt) {
                $ArrData['id'][$dt->requirement_id] = $dt->requirement_id;
                $ArrData['standards'][$dt->requirement_id][] = $dt;
            }
            $ArrStd = [];
            foreach ($Cross as $dtstd) {
                $ArrStd[$dtstd->requirement_id] = $dtstd;
            }

            $checklist = $this->AuditModel->getChecklistDetails($id);

            $this->template->set([
                'cklst'      => $cklst,
                'users'      => $users,
                'Cross'      => $Cross,
                'ArrData'    => $ArrData,
                'ArrStd'     => $ArrStd,
                'checklist'  => $checklist,
                'procedures' => $procedures,
            ]);

            $this->template->render('audit');
        } else {
            show_404();
        }
    }

    function results()
    {
        $this->template->set('title', 'Audit Results');
        $results = $this->AuditModel->getAuditResults();
        $details = $this->AuditModel->getAuditDetailsAll();

        $ArrDtl = [];
        if ($details) foreach ($details as $k => $v) {
            $ArrDtl[$v->audit_id][] = $v;
        }

        $data = [
            "results" => $results,
            'ArrDtl' => $ArrDtl,
        ];

        $this->template->render('results', $data);
    }

    function edit_audit($id)
    {
        if ($id) {
            $audit      = $this->AuditModel->getAuditByViewId($id);
            $cklst      = $this->AuditModel->getChecklistByViewId($audit->checklist_id);
            $users      = $this->AuditModel->getUsers($this->company);
            $procedures = $this->AuditModel->getProcedures($this->company);
            $Cross      = $this->AuditModel->getCrossReferences($cklst->procedure_id, $this->company);

            $ArrData = [];
            foreach ($Cross as $dt) {
                $ArrData['id'][$dt->requirement_id] = $dt->requirement_id;
                $ArrData['standards'][$dt->requirement_id][] = $dt;
            }
            $ArrStd = [];
            foreach ($Cross as $dtstd) {
                $ArrStd[$dtstd->requirement_id] = $dtstd;
            }

            $checklist = $this->AuditModel->getChecklistDetails($audit->checklist_id);
            $details   = $this->AuditModel->getAuditDetails($audit->id);
            $ArrDtl    = [];

            if ($details) foreach ($details as $d) {
                $ArrDtl[$d->checklist_detail_id] = $d;
            }

            $ArrDtlStd = [];
            foreach ($Cross as $s) {
                $ArrDtlStd[$s->requirement_id][] = $s;
            }

            $AdtAudit = $this->AuditModel->getNonChecklistAuditDetails($audit->id);

            $this->template->set([
                'cklst'      => $cklst,
                'users'      => $users,
                'audit'      => $audit,
                'Cross'      => $Cross,
                'ArrData'    => $ArrData,
                'ArrStd'     => $ArrStd,
                'procedures' => $procedures,
                'checklist'  => $checklist,
                'ArrDtl'     => $ArrDtl,
                'ArrDtlStd'  => $ArrDtlStd,
                'AdtAudit'   => $AdtAudit,
            ]);

            $this->template->render('audit');
        } else {
            show_404();
        }
    }

    function listPasal($procedure, $standard)
    {
        $data = $this->AuditModel->getChaptersByProcedure($procedure, $standard, $this->company);

        $html = '<option></option>';
        if ($data) {
            foreach ($data as $v) {
                $html .= "<option value='$v->id'>$v->chapter</option>";
            }
        }
        echo $html;
    }

    function saveAudit()
    {
        $data       = $this->input->post();
        $temuan     = isset($data['temuan']) ? $data['temuan'] : [];
        $detail     = isset($data['detail']) ? $data['detail'] : [];
        unset($data['temuan']);
        unset($data['detail']);

        $data['auditor'] = json_encode(isset($data['auditor']) ? $data['auditor'] : []);
        $data['auditee'] = json_encode(isset($data['auditee']) ? $data['auditee'] : []);

        if ($data) {
            $success = $this->AuditModel->saveAudit($data, $detail, $temuan, $this->auth->user_id());
            if ($success) {
                $return = array('status' => 1, 'msg' => 'Data has successfull saved. Thanks you.');
            } else {
                $return = array('status' => 0, 'msg' => 'Data has Failed save. Please Try Again!');
            }
        } else {
            $return = array('status' => 0, 'msg' => 'Data not valid. Please Try Again!');
        }
        echo json_encode($return);
    }

    function delete_audit()
    {
        $id = $this->input->post('id');
        if ($id) {
            $success = $this->AuditModel->deleteAudit($id);
            if ($success) {
                $Return = ['msg' => "Successfull delete data.", 'status' => 1];
            } else {
                $Return = ['msg' => "Failed deleting data, please try again.", 'status' => 0];
            }
        } else {
            $Return = ['msg' => "Data not valid", 'status' => 0];
        }
        echo json_encode($Return);
    }

    function view_audit($id)
    {
        if ($id) {
            $audit      = $this->AuditModel->getAuditByViewId($id);
            $cklst      = $this->AuditModel->getChecklistByViewId($audit->checklist_id);
            $users      = $this->AuditModel->getUsers($this->company);
            $procedures = $this->AuditModel->getProcedures($this->company);
            $Cross      = $this->AuditModel->getCrossReferences($cklst->procedure_id, $this->company);

            $ArrData = [];
            foreach ($Cross as $dt) {
                $ArrData['id'][$dt->requirement_id] = $dt->requirement_id;
                $ArrData['standards'][$dt->requirement_id][] = $dt;
            }
            $ArrStd = [];
            foreach ($Cross as $dtstd) {
                $ArrStd[$dtstd->requirement_id] = $dtstd;
            }

            $checklist = $this->AuditModel->getChecklistDetails($audit->checklist_id);
            $details   = $this->AuditModel->getAuditDetails($audit->id);
            $ArrDtl    = [];

            if ($details) foreach ($details as $d) {
                $ArrDtl[$d->checklist_detail_id] = $d;
            }

            $company_id = (isset($cklst->company_id)) ? $cklst->company_id : $this->company;
            $all_cross  = $this->AuditModel->getAllCrossReferences($company_id);
            
            $ArrDtlStd = [];
            $ArrPro = [];
            if ($all_cross) foreach ($all_cross as $c) {
                $ArrDtlStd[$c->requirement_id] = $c->name;
                $ArrPro[$c->id] = $c->chapter;
            }

            $AdtAudit = $this->AuditModel->getNonChecklistAuditDetails($audit->id);

            $category = [
                '0' => '<label class="label label-inline">OK</label>',
                '1' => '<label class="label label-inline label-warning">Minor</label>',
                '2' => '<label class="label label-inline label-danger">Major</label>',
                '3' => '<label class="label label-inline label-info">OFI</label>',
            ];

            $this->template->set([
                'cklst'     => $cklst,
                'users'     => $users,
                'audit'     => $audit,
                'ArrPro'    => $ArrPro,
                'ArrData'   => $ArrData,
                'ArrStd'    => $ArrStd,
                'ArrDtl'    => $ArrDtl,
                'ArrDtlStd' => $ArrDtlStd,
                'checklist' => $checklist,
                'category'  => $category,
                'AdtAudit'  => $AdtAudit,
                'company'  => $this->company,
            ]);

            $this->template->render('view');
        } else {
            show_404();
        }
    }

    function deleteNonChacklistAudit()
    {
        $id = $this->input->post('id');
        if ($id) {
            $success = $this->AuditModel->deleteNonChecklistAudit($id);
            if ($success) {
                $Return = ['msg' => "Successfull delete data.", 'status' => 1];
            } else {
                $Return = ['msg' => "Failed deleting data, please try again.", 'status' => 0];
            }
        } else {
            $Return = ['msg' => "Data not valid", 'status' => 0];
        }
        echo json_encode($Return);
    }

    public function uploadFile()
    {
        $upload_path = './directory/AUDIT/' . $this->company . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
            chmod($upload_path, 0755);
            if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
                chown($upload_path, 'www-data');
            }
        }

        $config['upload_path']     = $upload_path;
        $config['allowed_types']   = 'gif|jpg|png|jpeg';
        $config['max_size']        = '3068';
        $config['encryption_name'] = true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('document')) {
            $return = [
                'status' => 0,
                'msg'    => array('error' => $this->upload->display_errors()),
            ];
        } else {
            $data = $this->upload->data();
            if ($data) {
                $fileData = [
                    'file_name' => $data['file_name'],
                    'file_type' => $data['file_ext'],
                    'file_size' => $data['file_size'],
                ];
                $success = $this->AuditModel->updateAuditDetailFile($this->input->post('id'), $fileData);

                if ($success) {
                    $return = ['msg' => 'Upload Successfull!', 'status' => 1];
                    $this->session->set_flashdata('msg', 'Success Upload image delivery details.');
                } else {
                    $return = ['msg' => 'Failed Upload image delivery details. Please try again.', 'status' => 0];
                }
            }
        }
        echo json_encode($return);
    }
}
