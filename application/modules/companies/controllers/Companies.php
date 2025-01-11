<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Syamsudin
 * @copyright Copyright (c) 2021, Syamsudin
 *
 * This is controller for Perusahaan
 */

class Companies extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template->set([
            'title' => 'Companies',
            'icon' => 'fa fa-bulding'
        ]);

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $data = [];
        if ($this->company == '1') {
            $data = $this->db->get_where('companies')->result();
        } else {
            $data = $this->db->get_where('companies', ['id_perusahaan' => $this->company])->result();
        }

        $this->template->set('data', $data);
        $this->template->render('index');
    }

    //Create New Customer
    public function add()
    {
        $this->template->render('add');
    }

    public function save()
    {
        try {
            $post       = $this->input->post();
            $data       = $post;
            $branch     = isset($data['branch']) ? $data['branch'] : '';
            unset($data['branch']);
            $dataBranch = [];
            $this->db->trans_begin();
            if ($data) {
                if (isset($data['id_perusahaan']) && $data['id_perusahaan']) {
                    $data['modified_at'] = date('Y-m-d H:i:s');
                    $data['modified_by'] = $this->auth->user_id();
                    $this->db->update('companies', $data, ['id_perusahaan' => $data['id_perusahaan']]);
                } else {
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['created_by'] = $this->auth->user_id();
                    $this->db->insert('companies', $data);
                }

                /* Branch */
                if ($branch) foreach ($branch as $k => $val) {
                    $dataBranch = [
                        'branch_name' => $val['branch_name'],
                        'branch_name' => $val['branch_name'],
                        'branch_address' => $val['address'],
                        'branch_city' => $val['city'],
                    ];


                    if (isset($val['id'])) {
                        $dataBranch['company_id'] = $data['id_perusahaan'];
                        $dataBranch['modified_at'] = date('Y-m-d H:i:s');
                        $dataBranch['modified_by'] = $this->auth->user_id();
                        $this->db->update('company_branch', $dataBranch, ['id' => $val['id']]);
                    } else {
                        $dataBranch['company_id'] = ($data['id_perusahaan']) ?: $this->db->insert_id('companies');
                        $dataBranch['created_at'] = date('Y-m-d H:i:s');
                        $dataBranch['created_by'] = $this->auth->user_id();
                        $this->db->insert('company_branch', $dataBranch);
                    }
                }
            } else {
                $this->db->trans_rollback();
                $return        = array(
                    'status'        => 0,
                    'msg'            => 'Data not valid. Please Try Again!'
                );
                echo json_encode($return);
                return false;
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $return        = array(
                    'status'        => 0,
                    'msg'            => 'Data company Failed save. Please Try Again!'
                );
            } else {
                $this->db->trans_commit();
                $return        = array(
                    'status'        => 1,
                    'msg'            => 'Data company successfull saved. Thanks you.'
                );
            }
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            $return        = array(
                'status'        => 0,
                'msg'            => $th->getMessage(),
            );
        }

        echo json_encode($return);
    }

    //Edit Perusahaan
    public function edit($id = null)
    {
        $data = $this->db->get_where('companies', ['id_perusahaan' => $id])->row();
        $branch = $this->db->get_where('company_branch', ['company_id' => $id])->result();
        $this->template->render('edit', ['data' => $data, 'branch' => $branch]);
    }

    public function view($id = null)
    {
        $data = $this->db->get_where('companies', ['id_perusahaan' => $id])->row();
        $branch = $this->db->get_where('company_branch', ['company_id' => $id])->result();
        $this->template->render('view', ['data' => $data, 'branch' => $branch]);
    }

    public function load_data()
    {
        $data = $this->db->get_where('companies')->result();
        $this->template->set('data', $data);
        $this->template->render('load_data');
    }

    function hapus_perusahaan()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->uri->segment(3);

        if ($id != '') {

            $result = $this->Perusahaan_model->delete($id);

            $keterangan     = "SUKSES, Delete data Perusahaan " . $id;
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $id;
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $result = 0;
            $keterangan     = "GAGAL, Delete data Perusahaan " . $id;
            $status         = 0;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $id;
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }

        //Save Log
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        $param = array(
            'delete' => $result,
            'idx' => $id
        );

        echo json_encode($param);
    }
}
