<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_model extends BF_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /* FETCH METHODS */

    public function getProceduresByCompany($companyId)
    {
        return $this->db->get_where('procedures', ['company_id' => $companyId, 'status !=' => 'DEL'])->result();
    }

    public function getActiveDirectory($companyId)
    {
        return $this->db->order_by('created_at', 'ASC')->get_where('directory', [
            'parent_id' => '0',
            'active' => 'Y',
            'status !=' => 'DEL'
        ])->result();
    }

    public function getRecentFiles($limit = 10)
    {
        return $this->db->order_by('created_at', 'DESC')->get_where('directory', [
            'parent_id !=' => '0',
            'active' => 'Y',
            'flag_type' => 'FILE',
            'status !=' => 'DEL',
            'created_at like' => date('Y-m-d') . "%"
        ])->result();
    }

    public function getProcedureById($id)
    {
        return $this->db->get_where('procedures', ['id' => $id])->row();
    }

    public function getDirectoryLogs($id)
    {
        return $this->db->order_by('updated_at', 'ASC')->get_where('directory_log', ['directory_id' => $id])->result();
    }

    public function getProceduresByStatus($companyId, $status)
    {
        return $this->db->get_where('procedures', ['company_id' => $companyId, 'status' => $status])->result();
    }

    public function getProceduresByStatusAndDeletion($companyId, $status, $deletionStatus)
    {
        return $this->db->get_where('procedures', [
            'company_id' => $companyId,
            'status' => $status,
            'deletion_status' => $deletionStatus
        ])->result();
    }

    public function getAllUsers()
    {
        return $this->db->get_where('users')->result();
    }

    public function getPositionsByCompany($companyId)
    {
        return $this->db->get_where('positions', ['company_id' => $companyId])->result_array();
    }

    public function getGroupProcedures()
    {
        return $this->db->get_where('group_procedure')->result_array();
    }

    /* ACTION METHODS */

    private function _update_history($data)
    {
        $data['updated_by'] = $this->auth->user_id();
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('directory_log', $data);
    }

    public function review($data = null)
    {
        if ($data) {
            $thisData = $this->getProcedureById($data['id']);
            $update = [
                'status'      => $data['status'],
                'modified_by' => $this->auth->user_id(),
                'modified_at' => date('Y-m-d H:i:s'),
                'reviewed_by' => $this->auth->user_id(),
                'reviewed_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->update('procedures', $update, ['id' => $data['id']]);

            $log = [
                'directory_id' => $data['id'],
                'new_status'   => $data['status'],
                'old_status'   => $thisData->status,
                'doc_type'     => 'Procedure',
                'note'         => isset($data['note']) ? $data['note'] : ''
            ];
            $this->_update_history($log);
            return true;
        }
        return false;
    }

    public function approval($data = null)
    {
        if ($data) {
            $thisData = $this->getProcedureById($data['id']);
            $update = [
                'status'      => $data['status'],
                'modified_by' => $this->auth->user_id(),
                'modified_at' => date('Y-m-d H:i:s'),
                'approved_by' => $this->auth->user_id(),
                'approved_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->update('procedures', $update, ['id' => $data['id']]);

            $log = [
                'directory_id' => $data['id'],
                'new_status'   => $data['status'],
                'old_status'   => $thisData->status,
                'doc_type'     => 'Procedure',
                'note'         => isset($data['note']) ? $data['note'] : ''
            ];
            $this->_update_history($log);
            return true;
        }
        return false;
    }

    public function revision($data = null)
    {
        if ($data) {
            $thisData = $this->getProcedureById($data['id']);
            $update = [
                'status'          => $data['status'],
                'modified_by'     => $this->auth->user_id(),
                'modified_at'     => date('Y-m-d H:i:s'),
                'revision_req_by' => $this->auth->user_id(),
                'revision_req_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->update('procedures', $update, ['id' => $data['id']]);

            $log = [
                'directory_id' => $data['id'],
                'new_status'   => $data['status'],
                'old_status'   => $thisData->status,
                'doc_type'     => 'Procedure',
                'note'         => isset($data['note']) ? $data['note'] : ''
            ];
            $this->_update_history($log);
            return true;
        }
        return false;
    }

    public function deletion($data = null)
    {
        if ($data) {
            $thisData = $this->getProcedureById($data['id']);
            $update = [
                'status'          => $data['status'],
                'deletion_status' => 'OPN',
                'modified_by'     => $this->auth->user_id(),
                'modified_at'     => date('Y-m-d H:i:s'),
            ];
            $this->db->update('procedures', $update, ['id' => $data['id']]);

            $log = [
                'directory_id' => $data['id'],
                'new_status'   => $data['status'],
                'old_status'   => $thisData->status,
                'doc_type'     => 'Procedure',
                'note'         => isset($data['note']) ? $data['note'] : ''
            ];
            $this->_update_history($log);
            return true;
        }
        return false;
    }

    public function rev_deletion($data = null)
    {
        if ($data) {
            $thisData = $this->getProcedureById($data['id']);
            $update = [
                'deletion_status' => $data['deletion_status'],
                'modified_by'     => $this->auth->user_id(),
                'modified_at'     => date('Y-m-d H:i:s'),
            ];
            if (isset($data['status'])) $update['status'] = $data['status'];
            
            $this->db->update('procedures', $update, ['id' => $data['id']]);

            $log = [
                'directory_id' => $data['id'],
                'new_status'   => isset($data['status']) ? $data['status'] : $thisData->status,
                'old_status'   => $thisData->status,
                'doc_type'     => 'Procedure',
                'note'         => isset($data['note']) ? $data['note'] : ''
            ];
            $this->_update_history($log);
            return true;
        }
        return false;
    }

    public function updateDirectory($id, $data)
    {
        $res = $this->db->update('directory', $data, ['id' => $id]);
        $this->_update_history(['directory_id' => $id, 'new_status' => $data['status'], 'note' => 'Update status']);
        return $res;
    }

    public function getPictureById($id)
    {
        return $this->db->get_where('pictures', ['id' => $id])->row();
    }

    public function updatePicture($id, $data)
    {
        return $this->db->update('pictures', $data, ['id' => $id]);
    }
}
