<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Syamsudin
 * @copyright Copyright (c) 2021, Syamsudin
 *
 * This is controller for Perusahaan
 */

class Update_controls extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template->set([
            'title' => 'Update Controls',
            'icon' => 'fa fa-retweet'
        ]);

        $this->status = [
            'NA' => '<span class="badge badge-secondary">No Updates</span>',
            'PLN' => '<span class="badge badge-info">Planning</span>',
            'REV' => '<span class="badge badge-warning">Revision</span>',
            'REP' => '<span class="badge badge-danger">Invalidation</span>',
        ];

        date_default_timezone_set("Asia/Bangkok");
    }

    private function _getId()
    {
        $count  = 1;
        $sql    = "SELECT MAX(RIGHT(id,4)) as maxId FROM update_controls";
        $result = $this->db->query($sql)->row();
        if ($result->maxId > 0) {
            $count = $result->maxId + 1;
        }
        return "TSC" . str_pad($count, 4, "0", STR_PAD_LEFT);
    }

    public function management_sys()
    {
        $data = $this->db->get_where('update_controls', ['type' => 'SMS'])->result();
        $this->template->set('data', $data);
        $this->template->set('status', $this->status);
        $this->template->render('index');
    }

    public function regulations()
    {
        $data = $this->db->get_where('update_controls', ['type' => 'REG'])->result();
        $this->template->set('data', $data);
        $this->template->set('status', $this->status);
        $this->template->render('index_reg');
    }

    public function standards()
    {
        $data = $this->db->get_where('update_controls', ['type' => 'STD'])->result();
        $this->template->set('data', $data);
        $this->template->set('status', $this->status);
        $this->template->render('index_std');
    }

    public function load_requirements()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('requirements', ['id' => $id])->row();
        echo json_encode($data);
    }
    public function load_regulations()
    {
        $id     = $this->input->post('id');
        $data   = $this->db->get_where('regulations', ['id' => $id])->row();
        echo json_encode($data);
    }
    public function load_standards()
    {
        $id     = $this->input->post('id');
        $data   = $this->db->get_where('standards', ['id' => $id])->row();
        echo json_encode($data);
    }

    //Create New Customer
    public function add()
    {
        $data       = $this->db->get_where('requirements', ['status' => '1'])->result();
        $type       = 'REG';
        $label_name = 'Std. Management System';
        $this->template->set([
            'data'          => $data,
            'type'          => $type,
            'label_name'    => $label_name
        ]);
        $this->template->render('add');
    }

    public function add_update_reg()
    {
        $data       = $this->db->get_where('regulations', ['status !=' => 'DEL'])->result();
        $type       = 'REG';
        $label_name = 'Regulation Name';
        $this->template->set([
            'type'          => $type,
            'label_name'    => $label_name,
            'data'          => $data,
        ]);
        $this->template->render('add');
    }

    public function add_update_std()
    {
        $data       = $this->db->get_where('standards', ['status !=' => 'DEL'])->result();
        $type       = 'STD';
        $label_name = 'Standard Name';
        $this->template->set([
            'type'          => $type,
            'label_name'    => $label_name,
            'data'          => $data,
        ]);
        $this->template->render('add');
    }

    public function save()
    {
        $data       = $this->input->post();

        $this->db->trans_begin();
        if ($data) {
            if (isset($data['id']) && $data['id']) {
                $data['modified_at'] = date('Y-m-d H:i:s');
                $data['modified_by'] = $this->auth->user_id();
                $this->db->update('update_controls', $data, ['id' => $data['id']]);
            } else {
                $data['id'] = $this->_getId();
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = $this->auth->user_id();
                $this->db->insert('update_controls', $data);
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $return        = array(
                    'status'        => 0,
                    'msg'            => 'Data Update Controls Failed save. Please Try Again!'
                );
            } else {
                $this->db->trans_commit();
                $return        = array(
                    'status'        => 1,
                    'msg'            => 'Data Update Controls successfull saved. Thanks you.'
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

    //Edit Perusahaan
    public function edit($id = null)
    {
        $data = $this->db->get_where('requirements', ['status' => '1'])->result();
        $update = $this->db->get_where('update_controls', ['id' => $id])->row();
        $label_name = 'Std. Management System';
        $this->template->set([
            'data' => $data,
            'label_name'    => $label_name,
            'update' => $update,
        ]);

        $this->template->render('edit');
    }

    public function edit_reg($id = null)
    {
        $data = $this->db->get_where('regulations', ['status !=' => 'DEL'])->result();
        $update = $this->db->get_where('update_controls', ['id' => $id])->row();
        $label_name = 'Regulation Name';
        $this->template->set([
            'data' => $data,
            'label_name'    => $label_name,
            'update' => $update,
        ]);

        $this->template->render('edit');
    }

    public function edit_std($id = null)
    {
        $data = $this->db->get_where('standards', ['status !=' => 'DEL'])->result();
        $update = $this->db->get_where('update_controls', ['id' => $id])->row();
        $label_name = 'Standard Name';
        $this->template->set([
            'data' => $data,
            'label_name'    => $label_name,
            'update' => $update,
        ]);

        $this->template->render('edit');
    }

    public function view($id = null)
    {
        $data = $this->db->get_where('update_controls', ['id' => $id])->row();
        $this->template->set('data', $data);
        $this->template->render('view');
    }

    function delete()
    {
        $id = $this->input->post('id');

        if ($id != '') {
            $this->db->trans_begin();
            $this->db->delete('update_controls', ['id' => $id]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $Return = [
                    'status'    => 0,
                    'msg'       => "Failed save data Update Controls, please try again."
                ];
            } else {
                $this->db->trans_commit();
                $Return = [
                    'status'    => 1,
                    'msg'       => "Successfull save data Update Controls."
                ];
            }
        } else {
            $Return = [
                'status'    => 0,
                'msg'       => "Data not valid"
            ];
        }



        echo json_encode($Return);
    }
}
