<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Corrective_internal extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template->set([
            'title' => 'Corrective Action Internal',
            'icon' => 'fa fa-check-double'
        ]);
        $this->load->library('upload');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $data = $this->db->select('ci.*, d.department_name, u.full_name as pic_name')
            ->from('corrective_internal ci')
            ->join('audit_department d', 'd.id = ci.department_pic_car_id', 'left')
            ->join('users u', 'u.id_user = ci.pic_car_id', 'left')
            ->where('ci.company_id', $this->company)
            ->where('ci.deleted_at', null)
            ->group_by('ci.id')
            ->order_by('ci.id', 'DESC')
            ->get()
            ->result();

        $this->template->set('data', $data);
        $this->template->render('index');
    }

    public function add($id = null)
    {
        $depts = $this->db->get('audit_department')->result();
        $users = $this->db->get_where('view_users', ['company_id' => $this->company, 'status' => 'ACT'])->result();

        $data = null;
        $details = [];
        if ($id) {
            $data = $this->db->get_where('corrective_internal', ['id' => $id, 'company_id' => $this->company])->row();
            if ($data && !in_array($data->status, ['draft', 'reject'])) {
                redirect('corrective_internal');
                return;
            }
            $details = $this->db->order_by('urutan', 'ASC')->get_where('corrective_internal_detail', ['corrective_internal_id' => $id])->result();
        }

        $this->template->set([
            'title' => 'Corrective Action Internal - Form',
            'depts' => $depts,
            'users' => $users,
            'data'  => $data,
            'details' => $details,
        ]);
        $this->template->render('add');
    }

    public function save()
    {
        $post = $this->input->post();
        $action = $this->input->post('action'); // 'save' or 'submit'

        $this->db->trans_begin();

        $header = [
            'company_id'            => $this->company,
            'department_pembuat_id' => $post['department_pembuat_id'],
            'pic_pembuat_id'        => $post['pic_pembuat_id'],
            'tanggal_car'           => $post['tanggal_car'],
            'deadline_car'          => $post['deadline_car'],
            'pic_car_id'            => $post['pic_car_id'],
            'department_pic_car_id' => $post['department_pic_car_id'],
            'status'                => ($action == 'submit') ? 'waiting_approval' : 'draft',
        ];

        $car_id = isset($post['car_id']) ? $post['car_id'] : null;
        $old_evidence = [];

        if ($car_id) {
            // Update existing
            $header['modified_at'] = date('Y-m-d H:i:s');
            $header['modified_by'] = $this->auth->user_id();
            $this->db->update('corrective_internal', $header, ['id' => $car_id]);

            // Get old details to preserve evidence files
            $old_details = $this->db->order_by('urutan', 'ASC')->get_where('corrective_internal_detail', ['corrective_internal_id' => $car_id])->result();
            $old_evidence = [];
            foreach ($old_details as $od) {
                $old_evidence[$od->urutan] = [
                    'evidence_file' => $od->evidence_file,
                    'evidence_original_name' => $od->evidence_original_name,
                ];
            }

            // Delete old details
            $this->db->delete('corrective_internal_detail', ['corrective_internal_id' => $car_id]);
        } else {
            // Insert new
            $header['nomor_car'] = $this->_generateNomor();
            $header['created_at'] = date('Y-m-d H:i:s');
            $header['created_by'] = $this->auth->user_id();
            $this->db->insert('corrective_internal', $header);
            $car_id = $this->db->insert_id();

            // Log created
            $this->_addLog($car_id, 'created', 'CAR dibuat');
        }

        // Insert details
        if (isset($post['items'])) {
            $urutan = 0;
            foreach ($post['items'] as $index => $item) {
                $urutan++;
                $detail = [
                    'corrective_internal_id' => $car_id,
                    'urutan'                 => $urutan,
                    'deskripsi_masalah'      => $item['deskripsi_masalah'],
                    'fakta'                  => isset($item['fakta']) ? $item['fakta'] : '',
                    'kesimpulan_penyebab'    => isset($item['kesimpulan_penyebab']) ? $item['kesimpulan_penyebab'] : '',
                    'correction'             => isset($item['correction']) ? $item['correction'] : '',
                    'corrective_action'      => isset($item['corrective_action']) ? $item['corrective_action'] : '',
                    'created_at'             => date('Y-m-d H:i:s'),
                    'created_by'             => $this->auth->user_id(),
                ];

                // Handle file upload
                $file_key = "evidence_$index";
                if (isset($_FILES[$file_key]) && $_FILES[$file_key]['size'] > 0) {
                    $upload = $this->_uploadEvidence($file_key, $car_id);
                    if ($upload['status']) {
                        $detail['evidence_file'] = $upload['file_name'];
                        $detail['evidence_original_name'] = $upload['original_name'];
                    }
                } elseif (isset($old_evidence[$urutan])) {
                    // Preserve old evidence if no new file uploaded
                    $detail['evidence_file'] = $old_evidence[$urutan]['evidence_file'];
                    $detail['evidence_original_name'] = $old_evidence[$urutan]['evidence_original_name'];
                }

                $this->db->insert('corrective_internal_detail', $detail);
            }
        }

        // Log submitted
        if ($action == 'submit') {
            $this->_addLog($car_id, 'submitted', 'CAR diajukan untuk approval');
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 0, 'msg' => 'Gagal menyimpan data. Silakan coba lagi.']);
        } else {
            $this->db->trans_commit();
            $msg = ($action == 'submit') ? 'CAR berhasil diajukan.' : 'CAR berhasil disimpan sebagai draft.';
            echo json_encode(['status' => 1, 'msg' => $msg]);
        }
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $car = $this->db->get_where('corrective_internal', ['id' => $id, 'company_id' => $this->company])->row();

        if (!$car || !in_array($car->status, ['draft', 'reject'])) {
            echo json_encode(['status' => 0, 'msg' => 'Data tidak bisa dihapus.']);
            return;
        }

        $this->db->trans_begin();
        $this->db->update('corrective_internal', [
            'deleted_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->auth->user_id(),
        ], ['id' => $id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 0, 'msg' => 'Gagal menghapus data.']);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 1, 'msg' => 'Data berhasil dihapus.']);
        }
    }

    public function view($id = null)
    {
        $data = $this->db->get_where('corrective_internal', ['id' => $id, 'company_id' => $this->company])->row();
        if (!$data) {
            redirect('corrective_internal');
            return;
        }
        $details = $this->db->order_by('urutan', 'ASC')->get_where('corrective_internal_detail', ['corrective_internal_id' => $id])->result();
        $dept_pembuat = $this->db->get_where('audit_department', ['id' => $data->department_pembuat_id])->row();
        $dept_pic = $this->db->get_where('audit_department', ['id' => $data->department_pic_car_id])->row();
        $pic_pembuat = $this->db->get_where('view_users', ['id_user' => $data->pic_pembuat_id])->row();
        $pic_car = $this->db->get_where('view_users', ['id_user' => $data->pic_car_id])->row();

        $this->template->set([
            'data' => $data,
            'details' => $details,
            'dept_pembuat' => $dept_pembuat,
            'dept_pic' => $dept_pic,
            'pic_pembuat' => $pic_pembuat,
            'pic_car' => $pic_car,
        ]);
        $this->template->render('view');
    }

    private function _generateNomor()
    {
        $count = $this->db->where('company_id', $this->company)
            ->where('YEAR(created_at)', date('Y'))
            ->count_all_results('corrective_internal');
        return 'CAR/' . date('Y/m') . '/' . sprintf('%04d', $count + 1);
    }

    private function _addLog($car_id, $action, $note = '')
    {
        $this->db->insert('corrective_internal_log', [
            'corrective_internal_id' => $car_id,
            'action'    => $action,
            'note'      => $note,
            'action_by' => $this->auth->user_id(),
            'action_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function _uploadEvidence($field, $car_id)
    {
        $upload_path = FCPATH . 'directory/CAR/' . $this->company . '/' . $car_id;
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $config = [
            'upload_path'   => $upload_path,
            'allowed_types' => 'pdf|jpg|jpeg|png|doc|docx|xls|xlsx',
            'max_size'      => 10240, // 10MB
            'encrypt_name'  => true,
        ];

        $this->upload->initialize($config);

        if ($this->upload->do_upload($field)) {
            $file_data = $this->upload->data();
            return [
                'status'        => true,
                'file_name'     => $file_data['file_name'],
                'original_name' => $file_data['orig_name'],
            ];
        }
        return ['status' => false];
    }
}
