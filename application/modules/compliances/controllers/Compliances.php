<?php

use Mpdf\Mpdf;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Syamsudin
 * @copyright Copyright (c) 2021, Syamsudin
 *
 * This is controller for Perusahaan
 */

class Compliances extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('download');
        $this->load->library(array('upload', 'Image_lib'));
        $this->load->model(array(
            'Aktifitas/aktifitas_model',
            'compliances/Compliances_model',
        ));

        $this->template->set([
            'title' => 'Compliances',
            'icon' => 'fa fa-user-tie'
        ]);

        date_default_timezone_set("Asia/Bangkok");
    }

    private function _getId()
    {
        $count      = 1;
        $sql        = "SELECT MAX(RIGHT(id,4)) as maxId FROM compliances";
        $result     = $this->db->query($sql)->row();
        if ($result->maxId > 0) {
            $count = $result->maxId + 1;
        }
        return "COMP" . str_pad($count, 4, "0", STR_PAD_LEFT);
    }

    public function index_()
    {
        $reference = $this->db->get_where('view_references', ['status' => 'OPN'])->row();
        if ($reference) {
            $regulations = $this->db->get_where('view_ref_regulations', ['reference_id' => $reference->id])->result();
            $reviews     = $this->db->get_where('compilation_reviews', ['reference_id' => $reference->id])->result();
            $summary     = $this->db->order_by('last_review', 'DESC')->get_where('compilation_reviews', ['reference_id' => $reference->id])->row();
            $users       = $this->db->get_where('view_users', ['company_id' => $this->company, 'status' => 'ACT'])->result();
            // $ArrSub = [];
            // if($subjects) foreach($subjects as $sub){
            //     $ArrSub[$sub->id][];
            // }
            // $ArrSub = [];

        }

        $ArrUsers = [];

        foreach ($users as $usr) {
            $ArrUsers[$usr->id_user] = $usr->full_name;
        }

        $this->template->set([
            'regulations'   => $regulations,
            'reference'     => $reference,
            'reviews'       => $reviews,
            'summary'       => $summary,
            'ArrUsers'       => $ArrUsers,
        ]);

        $this->template->render('index');
    }

    public function index()
    {
        if (isset($_GET['b']) && $_GET['b']) {
            $ArrUsers = [];
            $regulations = '';
            $reference = '';
            $reviews = '';
            $summary = '';

            $reference = $this->Compliances_model->getReferenceById($_GET['b']);
            if ($reference) {
                $regulations    = $this->Compliances_model->getRefRegulations($reference->id);
                $reviews        = $this->Compliances_model->getCompilationReviews($reference->id);
                $users          = $this->Compliances_model->getActiveUsers($this->company);
                $summary        = $this->Compliances_model->getLatestReview($reference->id);
                $subjects       = $this->Compliances_model->getComplianceSubjects($reference->id);

                foreach ($users as $usr) {
                    $ArrUsers[$usr->id_user] = $usr->full_name;
                }

                $ArrReg = [];
                foreach ($regulations as $reg) {
                    $ArrReg[$reg->subject][] = $reg;
                }
            }

            $this->template->set([
                'regulations'   => $regulations ?: [],
                'reference'     => $reference ?: [],
                'reviews'       => $reviews ?: [],
                'summary'       => $summary ?: '',
                'ArrUsers'      => $ArrUsers,
                'subjects'      => $subjects,
                'ArrReg'        => $ArrReg,
            ]);
            $this->template->render('list');
        } else {
            $listCompliance = $this->Compliances_model->getReferences($this->company);
            $this->template->render('index', ['list' => $listCompliance]);
        }
    }

    public function lists($id = null)
    {
        $data = [];
        if ($id) {
            $reference = $this->Compliances_model->getReferenceById($id);
            $data = $this->Compliances_model->getCompliances($id, $reference->company_id);
        }

        $this->template->set([
            'reference' => $reference,
            'data' => $data
        ]);
        $this->template->render('list');
    }

    public function details($id = null)
    {
        $data = [];
        $complianceDtl = [];
        $ArrOpports = [];
        $ArrCompl = [];
        $ArrPasal = [];

        if ($id) {
            $compliance          = $this->Compliances_model->getComplianceById($id);

            if ($compliance) {
                $data            = $this->Compliances_model->getRegulationParagraphs($compliance->regulation_id);
                /* data phoaragraph */
                $complianceDtl       = $this->Compliances_model->getComplianceDetails($compliance->regulation_id, $compliance->reference_id);
                foreach ($complianceDtl as $dtl) {
                    $ArrCompl[$dtl->prgh_id] = $dtl;
                }

                foreach ($data as $dt) {
                    $ArrPasal[$dt->pasal_id][] = $dt;
                }

                $compOpports = $this->Compliances_model->getComplianceOpports($compliance->regulation_id);
                foreach ($compOpports as $opp) {
                    $ArrOpports[$opp->prgh_id][] = $opp;
                }
            }

            $users               = $this->Compliances_model->getActiveUsers($this->company);
        }

        $this->template->set([
            'data'          => $data,
            'ArrPasal'      => $ArrPasal,
            'users'         => $users,
            'compliance'    => $compliance,
            'ArrCompl'      => $ArrCompl,
            'ArrOpports'    => $ArrOpports,
        ]);

        $this->template->render('list-desc');
    }

    // public function add($comp_id = null)
    // {
    //     $regulations    = $this->db->get_where('view_ref_regulations', ['status' => 'OPN', 'company_id' => $comp_id])->result();
    //     $compDtl        = $this->db->get_where('view_compliances', ['company_id' => $this->company])->result();

    //     $ArrCompl = [];
    //     foreach ($compDtl as $dtl) {
    //         $ArrCompl[] = $dtl->regulation_id;
    //     }

    //     $this->template->set([
    //         'regulations' => $regulations,
    //         'ArrCompl' => $ArrCompl,
    //     ]);
    //     $this->template->render('add');
    // }

    // public function save_complience()
    // {
    //     $data = $this->input->post();
    //     $data['date']       = date('Y-m-d');
    //     $data['company_id'] = $this->company;
    //     if ($data) {
    //         $this->db->trans_begin();
    //         if (isset($data['id']) && $data['id']) {
    //             $data['modified_at']    = date('Y-m-d H:i:s');
    //             $data['modified_by']    = $this->auth->user_id();
    //             $this->db->update('compliances', $data, ['id' => $data['id']]);
    //         } else {
    //             $data['id']             = $this->_getId();
    //             $data['created_at']     = date('Y-m-d H:i:s');
    //             $data['created_by']     = $this->auth->user_id();
    //             $this->db->insert('compliances', $data);
    //         }

    //         if ($this->db->trans_status() === FALSE) {
    //             $this->db->trans_rollback();
    //             $return        = array(
    //                 'status'        => 0,
    //                 'msg'            => 'Compliance Failed save. Please Try Again!'
    //             );
    //         } else {
    //             $this->db->trans_commit();
    //             $return        = array(
    //                 'status'        => 1,
    //                 'msg'            => 'Compliance successfull saved. Thanks you.'
    //             );
    //         }
    //     } else {
    //         $this->db->trans_commit();
    //         $return        = array(
    //             'status'        => 0,
    //             'msg'            => 'Data not valid. Please Try Again!'
    //         );
    //     }
    //     echo json_encode($return);
    // }

    //Create New Customer
    // public function detail($id = null)
    // {
    //     if ($id) {
    //         $data = $this->db->get_where('view_references', ['id' => $id])->row();
    //         $regulations = $this->db->get_where('view_ref_regulations')->result();
    //         $users = $this->db->get_where('view_users', ['company_id' => $this->company, 'status' => 'ACT'])->result();

    //         $this->template->set([
    //             'data'          => $data,
    //             'regulations'   => $regulations,
    //             'users'         => $users,
    //         ]);
    //         $this->template->render('detail');
    //     } else {
    //     }
    // }

    // public function loadDesc($id = null)
    // {
    //     if ($id) {
    //         $pasal      = $this->db->get_where('regulation_pasal', ['regulation_id' => $id])->row();
    //         $data       = $this->db->get_where('view_regulation_paragraphs', ['regulation_id' => $id])->result();
    //         $users      = $this->db->get_where('view_users', ['company_id' => $this->company, 'status' => 'ACT'])->result();

    //         $ArrPasal   = [];
    //         foreach ($data as $dt) {
    //             $ArrPasal[$dt->pasal_id][] = $dt;
    //         }

    //         $this->template->set([

    //             'data'          => $data,
    //             'pasal'         => $pasal,
    //             'ArrPasal'      => $ArrPasal,
    //             'users'         => $users,
    //         ]);
    //         $this->template->render('list-desc');
    //     }
    // }
    public function save()
    {
        $data       = $this->input->post();
        $detailComp = [];
        $detailOpport = [];

        if (isset($data['detail'])) {
            foreach ($data['detail'] as $key => $dtl) {
                $detailComp[$key] = [
                    'id'                => isset($dtl['id']) ? $dtl['id'] : '',
                    'complience_id'     => $data['compliance_id'],
                    'reference_id'      => $data['reference_id'],
                    'company_id'        => $this->company,
                    'regulation_id'     => $data['regulation_id'],
                    'prgh_id'           => $dtl['prgh_id'],
                    'pasal_id'          => $dtl['pasal_id'],
                    'description'       => $dtl['description'],
                    'compliance_desc'   => $dtl['complience_desc'],
                    'status'            => ($dtl['status']) ?: null,
                ];
            }
        }

        if (isset($data['opport'])) {
            foreach ($data['opport'] as $key => $dtlOpp) {
                foreach ($dtlOpp as $dtl) {
                    $detailOpport[] = [
                        'id'                => isset($dtl['id']) ? $dtl['id'] : '',
                        'compliance_id'     => $data['compliance_id'],
                        'reference_id'      => $data['reference_id'],
                        'prgh_id'           => $dtl['prgh_id'],
                        'company_id'        => $this->company,
                        'regulation_id'     => $data['regulation_id'],
                        'category'          => $dtl['category'],
                        'description'       => $dtl['description'],
                        'action_plan'       => $dtl['action_plan'],
                        'pic'               => $dtl['pic'],
                        'due_date'          => $dtl['due_date'],
                    ];
                }
            }
        }

        if ($data) {
            $success = $this->Compliances_model->saveComplianceData($data, $detailComp, $detailOpport, $this->auth->user_id());
            if ($success) {
                $return = ['status' => 1, 'msg' => 'Data Detail Compliance successfully saved. Thank you.'];
            } else {
                $return = ['status' => 0, 'msg' => 'Data Detail Compliance Failed to save. Please Try Again!'];
            }
        } else {
            $return = ['status' => 0, 'msg' => 'Data not valid. Please Try Again!'];
        }
        echo json_encode($return);
    }

    public function saveFile()
    {
        $data = $this->input->post();
        $DIR_COMP = $this->company;
        $current_data = $this->Compliances_model->getFileDataById($data['id']);

        if (($_FILES) && $_FILES['file']['name']) {
            $upload_path = "./directory/COMPLIANCE/$DIR_COMP";
            if (!is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);

            $config = ['upload_path' => $upload_path, 'allowed_types' => 'pdf|xlsx|docx', 'encrypt_name' => true];
            $this->upload->initialize($config);

            if ($this->upload->do_upload('file')) {
                $file = $this->upload->data();
                $success = $this->Compliances_model->updateComplianceFile($data['id'], $file['file_name']);

                if ($success) {
                    if ($current_data->file && file_exists($upload_path . '/' . $current_data->file)) unlink($upload_path . '/' . $current_data->file);
                    echo json_encode(['status' => 1, 'msg' => "Save file successful."]);
                } else {
                    echo json_encode(['status' => 0, 'msg' => "FAILED!! Can't save file. Please try again."]);
                }
            } else {
                echo json_encode(['status' => 0, 'msg' => $this->upload->display_errors()]);
            }
        }
    }

    function file($file)
    {
        $id = $this->input->get('_init');
        if (isset($id) && $id) {
            $checkData = $this->db->get_where('compliance_details', ['id' => $id, 'file' => $file])->row();
            if ($checkData) {
                $this->load->view('file', ['id' => $id, 'file' => $file, 'comp' => $this->company]);
            } else {
                $this->error_page($id);
            }
        } else {
            $this->error_page($id);
        }
    }

    public function removeFile()
    {
        $id = $this->input->post('id');
        if (isset($id) && $id) {
            $data = $this->db->get_where('compliance_details', ['id' => $id])->row();
            if ($data) {
                $this->db->trans_begin();
                $this->db->update('compliance_details', ['file' => null], ['id' => $id]);

                if (file_exists("./directory/COMPLIANCE/$this->company/$data->file")) {
                    unlink("./directory/COMPLIANCE/$this->company/$data->file");
                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $Return = [
                        'status' => 0,
                        'msg'     => "FAILED!! Can't remove file. Please try again."
                    ];
                    echo json_encode($Return);
                } else {
                    $this->db->trans_commit();
                    $Return = [
                        'status' => 1,
                        'msg'     => "Remove file succesfull."
                    ];
                    echo json_encode($Return);
                }
            } else {
                $Return = [
                    'status' => 0,
                    'msg'     => "FAILED!! File not found."
                ];
            }
        } else {
            $Return = [
                'status' => 0,
                'msg'     => "FAILED!! File not found."
            ];
        }
    }

    private function error_page($data)
    {
        $this->load->view('errors/html/error_404_custome');
        return error_log('sdaa', 0, 'file compliance', $data);
    }

    public function view_compliance($id = null)
    {
        if ($id) {
            $reference      = $this->Compliances_model->getReferenceById($id);
            $regulations    = $this->Compliances_model->getComplianceDetailsByReference($reference->id);
            $opports        = $this->Compliances_model->getCompOpports($reference->id);
            $summary        = $this->Compliances_model->getLatestReview($reference->id);
            $users          = $this->Compliances_model->getActiveUsers($this->company);

            $cat            = ['OPP' => 'Peluang', 'RSK' => 'Resiko'];
            $status         = [
                'CMP' => '<span class="badge badge-success">Compliance</span>',
                'NCM' => '<span class="badge badge-danger">Not Compliance</span>',
                'NAP' => '<span class="badge badge-secondary">Not Applicable</span>'
            ];

            $ArrReg = $ArrOpports = $ArrUsers = [];
            foreach ($regulations as $reg) { $ArrReg[$reg->regulation_id][] = $reg; }
            foreach ($opports as $opr) { $ArrOpports[$opr->prgh_id][] = $opr; }
            foreach ($users as $usr) { $ArrUsers[$usr->id_user] = $usr->full_name; }

            $this->template->set([
                'reference' => $reference, 'regulations' => $regulations, 'ArrReg' => $ArrReg, 'ArrOpports' => $ArrOpports,
                'cat' => $cat, 'summary' => $summary, 'ArrUsers' => $ArrUsers, 'status' => $status,
            ]);
            $this->template->render('view_compilation');
        }
    }

    public function view_compliance_regulation($id = null)
    {
        $data = $complianceDtl = $ArrOpports = $ArrCompl = $ArrPasal = $ArrUsers = [];

        if ($id) {
            $compliance = $this->Compliances_model->getComplianceById($id);
            if ($compliance) {
                $data = $this->Compliances_model->getRegulationParagraphs($compliance->regulation_id);
                $complianceDtl = $this->Compliances_model->getComplianceDetails($compliance->regulation_id, $compliance->reference_id);
                foreach ($complianceDtl as $dtl) { $ArrCompl[$dtl->prgh_id] = $dtl; }
                foreach ($data as $dt) { $ArrPasal[$dt->pasal_id][] = $dt; }
                $compOpports = $this->Compliances_model->getComplianceOpports($compliance->regulation_id);
                foreach ($compOpports as $opp) { $ArrOpports[$opp->prgh_id][] = $opp; }
            }

            $users = $this->Compliances_model->getActiveUsers($this->company);
            foreach ($users as $usr) { $ArrUsers[$usr->id_user] = $usr->full_name; }
        }

        $status = [
            'CMP' => '<span class="badge badge-success">Compliance</span>',
            'NCM' => '<span class="badge badge-danger">Not Compliance</span>',
            'NAP' => '<span class="badge badge-secondary">Not Applicable</span>'
        ];

        $this->template->set([
            'data' => $data, 'ArrPasal' => $ArrPasal, 'ArrUsers' => $ArrUsers, 'compliance' => $compliance,
            'ArrCompl' => $ArrCompl, 'ArrOpports' => $ArrOpports, 'status' => $status,
        ]);
        $this->template->render('view_comp_regulation');
    }

    public function show_compilation($id = null, $status = null)
    {
        if ($id) {
            $reference = $this->Compliances_model->getReferenceById($id);
            $where = ['reference_id' => $reference->id];
            if ($status) { $where['status'] = $status; }

            $regulations    = $this->Compliances_model->getComplianceDetailsFiltered($where);
            $opports        = $this->Compliances_model->getCompOpports($reference->id);
            $users          = $this->Compliances_model->getActiveUsers($this->company);

            $cat            = ['OPP' => 'Peluang', 'RSK' => 'Resiko'];
            $statusBadge    = [
                'CMP' => '<span class="badge badge-success">Compliance</span>',
                'NCM' => '<span class="badge badge-danger">Not Compliance</span>',
                'NAP' => '<span class="badge badge-secondary">Not Applicable</span>'
            ];

            $ArrReg = $ArrOpports = $ArrUsers = [];
            foreach ($regulations as $reg) { $ArrReg[$reg->regulation_id][] = $reg; }
            foreach ($opports as $opr) { $ArrOpports[$opr->prgh_id][] = $opr; }
            foreach ($users as $usr) { $ArrUsers[$usr->id_user] = $usr->full_name; }

            $this->template->set([
                'reference' => $reference, 'regulations' => $regulations, 'ArrReg' => $ArrReg, 'ArrOpports' => $ArrOpports,
                'cat' => $cat, 'ArrUsers' => $ArrUsers, 'status' => $statusBadge,
            ]);
            $this->template->render('show-compilation');
        }
    }

    public function compilation($id = null)
    {
        if ($id) {
            $reference = $this->Compliances_model->getReferenceById($id);
            $regulations = $this->Compliances_model->getComplianceDetailsByReference($reference->id);
            $opports = $this->Compliances_model->getCompOpports($reference->id);
            $users = $this->Compliances_model->getActiveUsers($this->company);

            $cat = ['OPP' => 'Peluang', 'RSK' => 'Resiko'];
            $status = [
                'CMP' => '<span class="badge badge-success">Compliance</span>',
                'NCM' => '<span class="badge badge-danger">Not Compliance</span>',
                'NAP' => '<span class="badge badge-secondary">Not Applicable</span>'
            ];

            $ArrReg = $ArrOpports = $ArrUsers = [];
            foreach ($regulations as $reg) { $ArrReg[$reg->regulation_id][] = $reg; }
            foreach ($opports as $opr) { $ArrOpports[$opr->prgh_id][] = $opr; }
            foreach ($users as $usr) { $ArrUsers[$usr->id_user] = $usr->full_name; }

            $this->template->set([
                'reference' => $reference, 'regulations' => $regulations, 'ArrReg' => $ArrReg, 'ArrOpports' => $ArrOpports,
                'cat' => $cat, 'ArrUsers' => $ArrUsers, 'status' => $status,
            ]);
            $this->template->render('compilation');
        }
    }

    public function review($subject = null, $referenceId = null)
    {
        if ($subject) {
            $reference = $this->Compliances_model->getReferenceById($referenceId);
            $regulations = $this->Compliances_model->getComplianceDetailsFiltered(['reference_id' => $referenceId, 'subject' => $subject]);
            $users = $this->Compliances_model->getActiveUsers($this->company);

            $cat = ['OPP' => 'Peluang', 'RSK' => 'Resiko'];
            $status = [
                'CMP' => '<span class="badge badge-success">Compliance</span>',
                'NCM' => '<span class="badge badge-danger">Not Compliance</span>',
                'NAP' => '<span class="badge badge-secondary">Not Applicable</span>'
            ];

            $ArrReg = $ArrOpports = $ArrUsers = [];
            foreach ($regulations as $reg) {
                $ArrReg[$reg->regulation_id][] = $reg;
            }
            foreach ($users as $usr) {
                $ArrUsers[$usr->id_user] = $usr->full_name;
            }

            $this->template->set([
                'reference' => $reference,
                'regulations' => $regulations,
                'ArrReg' => $ArrReg,
                'ArrOpports' => $ArrOpports,
                'cat' => $cat,
                'ArrUsers' => $ArrUsers,
                'status' => $status,
                'subject' => $subject
            ]);
            $this->template->render('compilation');
        }
    }

    public function history($subject = null)
    {
        if ($subject) {
            $review = $this->Compliances_model->getCompilationReviewsBySubject($subject);
            $this->template->set(['review' => $review]);
            $this->template->render('history-review');
        }
    }

    public function save_review()
    {
        $mpdf = new Mpdf();
        $id = $this->input->post('id');
        $subject = $this->input->post('subject');
        $rand_text = uniqid(date('YmdHis-'));

        $mpdf->AddPage('L', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, '', 'Legal-L');

        if ($id) {
            $reference = $this->Compliances_model->getReferenceById($id);
            $regulations = $this->Compliances_model->getComplianceDetailsFiltered(['reference_id' => $reference->id, 'subject' => $subject]);
            $opports = $this->Compliances_model->getCompOpports($reference->id);
            $users = $this->Compliances_model->getActiveUsers($this->company);

            $cat = ['OPP' => 'Peluang', 'RSK' => 'Resiko'];
            $status = ['CMP' => 'Memenuhi', 'NCM' => 'Belum Memenuhi', 'NAP' => 'Tidak Teraplikasi'];

            $ArrReg = $ArrOpports = $ArrUsers = [];
            $TC = $TNC = $TNA = 0;
            $refRegs = $this->Compliances_model->getRefRegulations($id);
            foreach ($refRegs as $rr) {
                if ($rr->subject == $subject) {
                    $TC += $rr->total_compliance;
                    $TNC += $rr->total_not_compliance;
                    $TNA += $rr->total_not_applicable;
                }
            }

            foreach ($regulations as $reg) {
                $ArrReg[$reg->regulation_id][] = $reg;
            }

            $summary = ['TC' => $TC, 'TNC' => $TNC, 'TNA' => $TNA];
            foreach ($opports as $opr) { $ArrOpports[$opr->prgh_id][] = $opr; }
            foreach ($users as $usr) { $ArrUsers[$usr->id_user] = $usr->full_name; }

            $page = $this->load->view('export-pdf', compact('reference', 'regulations', 'ArrReg', 'ArrOpports', 'cat', 'ArrUsers', 'summary', 'status'), TRUE);

            $Review = [
                'reference_id' => $id, 'company_id' => $reference->company_id, 'last_review' => date('Y-m-d H:i:s'),
                'subject' => $subject, 'total_compliance' => $TC, 'total_not_compliance' => $TNC, 'total_not_applicable' => $TNA,
                'document' => $rand_text . '.pdf',
            ];

            $this->db->trans_begin();
            $this->Compliances_model->insertReview($Review);
            $count = count($this->Compliances_model->getCompilationReviews($id));
            $this->Compliances_model->updateReferenceReview($id, $count, $Review['last_review'], $this->auth->user_id());

            if ($this->db->trans_status() === FALSE) {
                $error = $this->db->error();
                $this->db->trans_rollback();
                echo json_encode(['status' => 0, 'msg' => 'Compliance Failed save. Please Try Again! Error: ' . (isset($error['message']) ? $error['message'] : 'Unknown error')]);
            } else {
                $this->db->trans_commit();
                $mpdf->WriteHTML($page);
                $dir = "./directory/COMPILATIONS";
                if (!is_dir($dir)) mkdir($dir, 0755, TRUE);
                $mpdf->Output($dir . "/" . $rand_text . ".pdf", 'F');
                echo json_encode(['status' => 1, 'msg' => 'Compliance successfully saved. Thank you.']);
            }
        } else {
            echo json_encode(['status' => 0, 'msg' => 'Data Not Valid. Please Try Again!']);
        }
    }

    public function export_pdf($id = null, $status = null)
    {
        $mpdf = new Mpdf();
        $mpdf->AddPage('L', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, '', 'Legal-L');

        $where = ['reference_id' => $id];
        if ($status) $where['status'] = $status;

        if ($id) {
            $reference = $this->Compliances_model->getReferenceById($id);
            $regulations = $this->Compliances_model->getComplianceDetailsFiltered($where);
            $opports = $this->Compliances_model->getCompOpports($reference->id);
            $users = $this->Compliances_model->getActiveUsers($this->company);
            $subject = $this->Compliances_model->getAllComplianceSubjects($this->company);

            $refRegs = $this->Compliances_model->getRefRegulations($id);
            foreach ($refRegs as $rr) {
                $TC += $rr->total_compliance;
                $TNC += $rr->total_not_compliance;
                $TNA += $rr->total_not_applicable;
            }

            $summary = ['TC' => $TC, 'TNC' => $TNC, 'TNA' => $TNA];
            $cat = ['OPP' => 'Peluang', 'RSK' => 'Resiko'];
            $statusText = ['CMP' => 'Memenuhi', 'NCM' => 'Belum Memenuhi', 'NAP' => 'Tidak Teraplikasi'];

            $ArrReg = $ArrOpports = $ArrUsers = [];
            foreach ($regulations as $reg) { $ArrReg[$reg->subject][] = $reg; }
            foreach ($opports as $opr) { $ArrOpports[$opr->prgh_id][] = $opr; }
            foreach ($users as $usr) { $ArrUsers[$usr->id_user] = $usr->full_name; }

            $page = $this->load->view('export-pdf', compact('reference', 'regulations', 'ArrReg', 'ArrOpports', 'cat', 'ArrUsers', 'summary', 'statusText', 'subject'), TRUE);
            $mpdf->WriteHTML($page);
        } else {
            $mpdf->WriteHTML("Data not valid");
        }
        $mpdf->Output();
    }
}
