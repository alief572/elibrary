<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Menus
 */

class Menus extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'Menus/Menus_model',
            'Aktifitas/aktifitas_model'
        ));
        $this->template->title('Manage Data Menus');
        $this->template->page_icon('fa fa-bars');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $this->auth->restrict();

        $data = $this->Menus_model->get_menus_data();

        $this->template->set('results', $data);
        $this->template->title('Manage Data Menus');
        $this->template->render('list');
    }

    // Create New Menu Form
    public function create()
    {
        $this->auth->restrict();

        $datgroupmenu = $this->Menus_model->pilih_menu_group();
        $parent_id    = $this->Menus_model->pilih_parent();

        $this->template->set('datgroupmenu', $datgroupmenu);
        $this->template->set('parent', $parent_id);
        $this->template->title('Input Master Menu');
        $this->template->render('menus_form');
    }

    // Edit Menu Form
    public function edit()
    {
        $this->auth->restrict();

        $id = $this->uri->segment(3);
        $data = $this->Menus_model->find_by(array('id' => $id));
        if (!$data) {
            $this->template->set_message("Invalid ID Menu", 'error');
            redirect('menus');
        }

        $datgroupmenu = $this->Menus_model->pilih_menu_group();
        $parent_id    = $this->Menus_model->pilih_parent($id);

        $this->template->set('datgroupmenu', $datgroupmenu);
        $this->template->set('parent', $parent_id);
        $this->template->set('data', $data);
        $this->template->title('Edit Master Menu');
        $this->template->render('menus_form');
    }

    // Save Data Menus (AJAX)
    public function save_data_Menus()
    {
        $this->auth->restrict();

        $type       = $this->input->post("type");
        $id         = $this->input->post("id");
        $title      = trim($this->input->post("title"));
        $link       = trim($this->input->post("link"));
        $icon       = trim($this->input->post('icon'));
        $target     = $this->input->post('target');
        $group_menu = $this->input->post('group_menu');
        $parent_id  = $this->input->post('parent_id');
        $status     = $this->input->post('status');
        $order      = $this->input->post('order');

        if (empty($title) || empty($link)) {
            echo json_encode(array(
                'status' => 0,
                'save'   => 0,
                'msg'    => 'Nama Menu dan Link/Path wajib diisi!'
            ));
            return;
        }

        if ($type == "edit") {
            if (!empty($id)) {
                $data = array(
                    'title'      => $title,
                    'link'       => $link,
                    'icon'       => $icon,
                    'target'     => $target,
                    'group_menu' => $group_menu,
                    'parent_id'  => $parent_id,
                    'status'     => $status,
                    'order'      => (int)$order,
                );

                $result = $this->Menus_model->update($id, $data);
                $keterangan     = "SUKSES, Edit data Menu ".$id.", Title : ".$title;
                $log_status     = 1;
                $nm_hak_akses   = "Menus.Manage";
                $kode_universal = $id;
                $jumlah         = 1;
                $sql            = $this->db->last_query();
            } else {
                $result         = FALSE;
                $keterangan     = "GAGAL, Edit data Menu, ID kosong";
                $log_status     = 0;
                $nm_hak_akses   = "Menus.Manage";
                $kode_universal = $id;
                $jumlah         = 0;
                $sql            = $this->db->last_query();
            }

            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $log_status);
        } else { // Add New
            $data = array(
                'title'      => $title,
                'link'       => $link,
                'icon'       => $icon,
                'target'     => $target,
                'group_menu' => $group_menu,
                'parent_id'  => $parent_id,
                'status'     => $status,
                'order'      => (int)$order,
            );

            $new_id = $this->Menus_model->insert($data);
            if (is_numeric($new_id) && $new_id > 0) {
                $keterangan     = "SUKSES, Tambah data Menu ".$new_id.", Title : ".$title;
                $log_status     = 1;
                $nm_hak_akses   = "Menus.Add";
                $kode_universal = $new_id;
                $jumlah         = 1;
                $sql            = $this->db->last_query();
                $result         = TRUE;
            } else {
                $keterangan     = "GAGAL, Tambah data Menu : ".$title;
                $log_status     = 0;
                $nm_hak_akses   = "Menus.Add";
                $kode_universal = 'NewData';
                $jumlah         = 0;
                $sql            = $this->db->last_query();
                $result         = FALSE;
            }

            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $log_status);
        }

        $param = array(
            'status' => $result ? 1 : 0,
            'save'   => $result ? 1 : 0,
            'msg'    => $result ? 'Data Menu berhasil disimpan!' : 'Gagal menyimpan Data Menu!'
        );
        echo json_encode($param);
    }

    // Delete Menu (AJAX)
    public function hapus_Menus()
    {
        $this->auth->restrict();

        $id = $this->uri->segment(3);
        if (!empty($id)) {
            // Check if this menu has active sub-menus
            $child_count = $this->Menus_model->count_by(array('parent_id' => $id));
            if ($child_count > 0) {
                echo json_encode(array(
                    'status' => 0,
                    'delete' => 0,
                    'msg'    => "Menu ini masih memiliki {$child_count} sub-menu! Hapus atau pindahkan sub-menu terlebih dahulu."
                ));
                return;
            }

            $result = $this->Menus_model->delete($id);
            $keterangan     = "SUKSES, Delete data Menu ID: ".$id;
            $log_status     = 1;
            $nm_hak_akses   = "Menus.Delete";
            $kode_universal = $id;
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $result         = 0;
            $keterangan     = "GAGAL, Delete data Menu ID kosong";
            $log_status     = 0;
            $nm_hak_akses   = "Menus.Delete";
            $kode_universal = $id;
            $jumlah         = 0;
            $sql            = $this->db->last_query();
        }

        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $log_status);

        $param = array(
            'status' => $result ? 1 : 0,
            'delete' => $result ? 1 : 0,
            'idx'    => $id,
            'msg'    => $result ? 'Data Menu berhasil dihapus!' : 'Gagal menghapus Data Menu!'
        );
        echo json_encode($param);
    }
}
