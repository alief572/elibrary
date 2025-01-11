<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Syamsudin
 * @copyright Copyright (c) 2021, Syamsudin
 *
 * This is controller for Perusahaan
 */

class Action_plan extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template->set([
            'title' => 'Action Plan',
            'icon' => 'fa fa-list'
        ]);

        date_default_timezone_set("Asia/Bangkok");
    }

    private function _getId()
    {
        $count  = 1;
        $sql    = "SELECT MAX(RIGHT(id,4)) as maxId FROM scopes";
        $result = $this->db->query($sql)->row();
        if ($result->maxId > 0) {
            $count = $result->maxId + 1;
        }
        return "SCP" . str_pad($count, 4, "0", STR_PAD_LEFT);
    }

    public function index()
    {
        $status = [
            'OPN' => '<span class="badge badge-info">OPEN</span>',
            'PRO' => '<span class="badge badge-primary">PROGRESS</span>',
            'CNL' => '<span class="badge badge-secondary">CANCEL</span>',
            'DNE' => '<span class="badge badge-success">DONE</span>'
        ];
        $data = $this->db->get_where('compliance_opports', ['company_id' => $this->company])->result();
        $users = $this->db->get_where('users', ['status' => 'ACT'])->result();
        $ArrUsers = [];
        foreach ($users as $usr) {
            $ArrUsers[$usr->id_user] = $usr->full_name;
        }

        $this->template->set([
            'data'      => $data,
            'status'    => $status,
            'ArrUsers'  => $ArrUsers,
        ]);

        $this->template->render('index');
    }

    //Create New Customer
    public function add()
    {
        $this->template->render('add');
    }

    public function save()
    {
        $data       = $this->input->post();

        $this->db->trans_begin();
        if ($data) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = $this->auth->user_id();
            $this->db->update('compliance_opports', $data, ['id' => $data['id']]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $return        = array(
                    'status'        => 0,
                    'msg'            => 'Data Action Plan Failed save. Please Try Again!'
                );
            } else {
                $this->db->trans_commit();
                $return        = array(
                    'status'        => 1,
                    'msg'            => 'Data Action Plan successfull saved. Thanks you.'
                );
            }
        } else {
            $this->db->trans_commit();
            $return        = array(
                'status'        => 0,
                'msg'            => 'Data not valid. Please Try Again!'
            );
        }
        echo json_encode($return);
    }

    public function update($id = null)
    {
        $opport         = $this->db->get_where('compliance_opports', ['id' => $id])->row();
        $detailComp     = $this->db->get_where('compliance_details', ['prgh_id' => $opport->prgh_id])->row();
        $regulation     = $this->db->get_where('regulations', ['id' => $opport->regulation_id])->row();
        $prgh           = $this->db->get_where('regulation_paragraphs', ['id' => $opport->prgh_id])->row();
        $pasal          = $this->db->get_where('regulation_pasal', ['id' => $detailComp->pasal_id])->row();
        $company        = $this->db->get_where('companies', ['id_perusahaan' => $opport->company_id])->row();
        $users          = $this->db->get_where('view_users', ['company_id' => $this->company, 'status' => 'ACT'])->result();

        $cat = [
            'OPP' => 'Opportunity',
            'RSK' => 'Risk',
        ];

        $status = [
            'NCM' => 'Non Compliance',
            'CMP' => 'Compliance',
            'NAP' => 'Not Applicable',
        ];

        $ArrUsers = [];
        foreach ($users as $usr) {
            $ArrUsers[$usr->id_user] = $usr->full_name;
        }

        $this->template->set([
            'opport'        => $opport,
            'company'       => $company,
            'detailComp'    => $detailComp,
            'regulation'    => $regulation,
            'ArrUsers'      => $ArrUsers,
            'status'        => $status,
            'prgh'          => $prgh,
            'pasal'         => $pasal,
            'cat'           => $cat,
        ]);
        $this->template->render('update');
    }

    public function view($id = null)
    {
        $data = $this->db->get_where('scopes', ['id' => $id])->row();
        $this->template->set('data', $data);
        $this->template->render('view');
    }

    function delete()
    {
        $id = $this->input->post('id');
        $this->db->trans_begin();

        if ($id != '') {
            $this->db->delete('scopes', ['id' => $id]);
        } else {
            $this->db->trans_rollback();
            $Return = [
                'msg'       => "Data not valid",
                'status'    => 0
            ];
            echo json_encode($Return);
            return false;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $Return = [
                'msg'       => "Successfull save data scopes.",
                'status'    => 0
            ];
        } else {
            $this->db->trans_commit();
            $Return = [
                'msg'       => "Failed save data scopes, please try again.",
                'status'    => 1
            ];
        }

        echo json_encode($Return);
    }
}
