<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Approval_corrective_internal extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template->set([
            'title' => 'Approval Corrective Action Internal',
            'icon' => 'fa fa-check-double'
        ]);

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
            ->where('ci.status', 'waiting_approval')
            ->group_by('ci.id')
            ->order_by('ci.id', 'DESC')
            ->get()
            ->result();

        $this->template->set('data', $data);
        $this->template->render('index');
    }

    public function view($id = null)
    {
        $data = $this->db->get_where('corrective_internal', ['id' => $id, 'company_id' => $this->company])->row();
        if (!$data) {
            redirect('approval_corrective_internal');
            return;
        }
        $details = $this->db->order_by('urutan', 'ASC')->get_where('corrective_internal_detail', ['corrective_internal_id' => $id])->result();
        $dept_pembuat = $this->db->get_where('audit_department', ['id' => $data->department_pembuat_id])->row();
        $dept_pic = $this->db->get_where('audit_department', ['id' => $data->department_pic_car_id])->row();
        $pic_pembuat = $this->db->get_where('view_users', ['id_user' => $data->pic_pembuat_id])->row();
        $pic_car = $this->db->get_where('view_users', ['id_user' => $data->pic_car_id])->row();

        $this->template->set([
            'title' => 'Corrective Action Internal - Detail',
            'data' => $data,
            'details' => $details,
            'dept_pembuat' => $dept_pembuat,
            'dept_pic' => $dept_pic,
            'pic_pembuat' => $pic_pembuat,
            'pic_car' => $pic_car,
        ]);
        $this->template->render('view');
    }

    public function approve($id = null)
    {
        $data = $this->db->get_where('corrective_internal', ['id' => $id, 'company_id' => $this->company])->row();
        if (!$data) {
            redirect('approval_corrective_internal');
            return;
        }
        $details = $this->db->order_by('urutan', 'ASC')->get_where('corrective_internal_detail', ['corrective_internal_id' => $id])->result();
        $dept_pembuat = $this->db->get_where('audit_department', ['id' => $data->department_pembuat_id])->row();
        $dept_pic = $this->db->get_where('audit_department', ['id' => $data->department_pic_car_id])->row();
        $pic_pembuat = $this->db->get_where('view_users', ['id_user' => $data->pic_pembuat_id])->row();
        $pic_car = $this->db->get_where('view_users', ['id_user' => $data->pic_car_id])->row();

        $this->template->set([
            'title' => 'Corrective Action Internal - Approval',
            'data' => $data,
            'details' => $details,
            'dept_pembuat' => $dept_pembuat,
            'dept_pic' => $dept_pic,
            'pic_pembuat' => $pic_pembuat,
            'pic_car' => $pic_car,
        ]);
        $this->template->render('approve');
    }

    public function do_approve()
    {
        $id = $this->input->post('id');
        $action = $this->input->post('action');
        $alasan = $this->input->post('alasan_reject');

        $car = $this->db->get_where('corrective_internal', ['id' => $id, 'company_id' => $this->company])->row();
        if (!$car || $car->status != 'waiting_approval') {
            echo json_encode(['status' => 0, 'msg' => 'Data tidak valid atau sudah diproses.']);
            return;
        }

        $this->db->trans_begin();

        if ($action == 'approve') {
            $this->db->update('corrective_internal', [
                'status'      => 'closed',
                'approved_by' => $this->auth->user_id(),
                'approved_at' => date('Y-m-d H:i:s'),
                'modified_at' => date('Y-m-d H:i:s'),
                'modified_by' => $this->auth->user_id(),
            ], ['id' => $id]);

            $this->db->insert('corrective_internal_log', [
                'corrective_internal_id' => $id,
                'action'    => 'approved',
                'note'      => 'CAR di-approve',
                'action_by' => $this->auth->user_id(),
                'action_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $this->db->update('corrective_internal', [
                'status'        => 'reject',
                'alasan_reject' => $alasan,
                'rejected_by'   => $this->auth->user_id(),
                'rejected_at'   => date('Y-m-d H:i:s'),
                'modified_at'   => date('Y-m-d H:i:s'),
                'modified_by'   => $this->auth->user_id(),
            ], ['id' => $id]);

            $this->db->insert('corrective_internal_log', [
                'corrective_internal_id' => $id,
                'action'    => 'rejected',
                'note'      => $alasan,
                'action_by' => $this->auth->user_id(),
                'action_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 0, 'msg' => 'Gagal memproses. Silakan coba lagi.']);
        } else {
            $this->db->trans_commit();
            $msg = ($action == 'approve') ? 'CAR berhasil di-approve.' : 'CAR berhasil di-reject.';
            echo json_encode(['status' => 1, 'msg' => $msg]);
        }
    }
}
