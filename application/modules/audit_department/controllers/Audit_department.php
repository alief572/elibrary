<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Hikmat
 * @copyright Copyright (c) 2024, Hikmat
 *
 */

class Audit_department extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template->set([
            'title' => 'Department',
            'icon' => 'fa fa-building'
        ]);

        date_default_timezone_set("Asia/Bangkok");
    }

    private function _getId()
    {
        $count    = 1;
        $result   = $this->db->select('MAX(RIGHT(id,3)) as id')->from('departements')->where(['SUBSTR(id,3,4)' => date('ym')])->get()->row();

        if ($result && $result->id > 0) {
            $count = (int)$result->id + 1;
        }
        return "AD" . date('ym-') . sprintf("%03d", $count);
    }

    public function index()
    {
        $data = $this->db->select('id, company_id, name, name as department_name, status')
            ->get_where('departements', ['status !=' => '0'])
            ->result();
        $this->template->set('data', $data);
        $this->template->render('index');
    }

    public function add()
    {
        $this->template->render('add');
    }

    public function edit($id)
    {
        $data = $this->db->select('id, company_id, name, name as department_name, status')
            ->get_where('departements', ['id' => $id])
            ->row();
        $this->template->set([
            'data' => $data,
        ]);
        $this->template->render('edit');
    }

    public function save()
    {
        $data = $this->input->post();

        $this->db->trans_begin();
        if ($data) {
            // Support both department_name and name
            if (isset($data['department_name']) && !isset($data['name'])) {
                $data['name'] = $data['department_name'];
            }
            unset($data['department_name']);

            if (isset($data['id']) && $data['id']) {
                $data['modified_at'] = date('Y-m-d H:i:s');
                $data['modified_by'] = $this->auth->user_id();
                $this->db->update('departements', $data, ['id' => $data['id']]);
            } else {
                $data['id']         = $this->_getId();
                if (!isset($data['company_id']) || empty($data['company_id'])) {
                    $data['company_id'] = $this->company ?: 1;
                }
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = $this->auth->user_id();
                $this->db->insert('departements', $data);
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $return = array(
                    'status' => 0,
                    'msg'    => 'Data has Failed save. Please Try Again!'
                );
            } else {
                $this->db->trans_commit();
                $return = array(
                    'status' => 1,
                    'msg'    => 'Data has successfull saved. Thanks you.'
                );
            }
        } else {
            $this->db->trans_commit();
            $return = array(
                'status' => 0,
                'msg'    => 'Data not valid. Please Try Again!'
            );
        }
        echo json_encode($return);
    }

    function delete()
    {
        $id = $this->input->post('id');
        if (empty($id)) {
            $id = $this->input->get('id');
        }
        if (empty($id)) {
            $raw = json_decode($this->input->raw_input_stream, true);
            if (!empty($raw['id'])) {
                $id = $raw['id'];
            }
        }

        if ($id) {
            $this->db->trans_begin();
            $this->db->update('departements', [
                'status'      => '0',
                'modified_at' => date('Y-m-d H:i:s'),
                'modified_by' => $this->auth->user_id()
            ], ['id' => $id]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $Return = [
                    'msg'    => "Failed delete data department, please try again.",
                    'status' => 0
                ];
            } else {
                $this->db->trans_commit();
                $Return = [
                    'msg'    => "Successfull delete data department.",
                    'status' => 1
                ];
            }
        } else {
            $this->db->trans_rollback();
            $Return = [
                'msg'    => "Data not valid",
                'status' => 0
            ];
        }

        echo json_encode($Return);
    }
}
