<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Hikmat
 * @copyright Copyright (c) 2024, Hikmat
 *
 */

class Audit_company extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template->set([
            'title' => 'Company Audit',
            'icon' => 'fa fa-building'
        ]);

        date_default_timezone_set("Asia/Bangkok");
    }

    private function _getId()
    {
        $count    = 1;
        $result   = $this->db->select('MAX(RIGHT(id,3)) as id')->from('audit_company')->where(['SUBSTR(id,3,4)'=> date('ym')])->get()->row();

        if ($result->id > 0) {
            $count = $result->id + 1;
        }
        return "CA" . date('ym-') . sprintf("%03d", $count);
    }
    
    public function index()
    {
        $data = $this->db->get_where('audit_company',['status'=> '1'])->result();
        $this->template->set('data', $data);
        $this->template->render('index');
    }

    public function add()
    {
        $axist_company  = $this->db->get_where('audit_temuan', ['status' => 1])->result_array();
        $arr_comp       = array_column($axist_company, 'company_id');
        $companies      = $this->db->where_not_in('id', ($arr_comp) ?: 1)->get_where('audit_company', ['status' => '1'])->result();
        $badan          = $this->db->get_where('audit_badan_sertifikasi', ['status' => '1'])->result();

        $this->template->set('badan', $badan);
        $this->template->set('companies', $companies);
        $this->template->render('add');
    }
    
    public function edit($id)
    {
        $data               = $this->db->get_where('audit_company', ['id' => $id, 'status' => '1'])->row();
       
        $this->template->set([
            'data'              => $data,
        ]);
        $this->template->render('edit');
    }

    public function save()
    {
        $data       = $this->input->post();
      
        $this->db->trans_begin();
        if ($data) {
            if (isset($data['id']) && $data['id']) {
                $data['modified_at'] = date('Y-m-d H:i:s');
                $data['modified_by'] = $this->auth->user_id();
                $this->db->update('audit_company', $data, ['id' => $data['id']]);
            } else {
                $data['id']         = $this->_getId();
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = $this->auth->user_id();
                $this->db->insert('audit_company', $data);
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $return        = array(
                    'status'        => 0,
                    'msg'            => 'Data has Failed save. Please Try Again!'
                );
            } else {
                $this->db->trans_commit();
                $return        = array(
                    'status'        => 1,
                    'msg'            => 'Data has successfull saved. Thanks you.'
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

    function delete()
    {
        $id = $this->input->post('id');
        if ($id) {
            $this->db->trans_begin();
            $this->db->update('audit_company', ['status' => '0'], ['id' => $id]);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $Return = [
                    'msg'       => "Successfull save data audit company.",
                    'status'    => 0
                ];
            } else {
                $this->db->trans_commit();
                $Return = [
                    'msg'       => "Failed save data audit company, please try again.",
                    'status'    => 1
                ];
            }
        } else {
            $this->db->trans_rollback();
            $Return = [
                'msg'       => "Data not valid",
                'status'    => 0
            ];
        }

        echo json_encode($Return);
    }

}
