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
        return $this->db->select('directory_log.*, users.full_name')
            ->from('directory_log')
            ->join('users', 'users.id_user = directory_log.updated_by', 'left')
            ->where('directory_log.directory_id', $id)
            ->order_by('directory_log.updated_at', 'ASC')
            ->get()
            ->result();
    }

    public function getProcedureRevisions($procedureId)
    {
        if (!$this->db->table_exists('procedure_history_revisions')) {
            $this->_createProcedureHistoryRevisionsTable();
        }

        $revisions = $this->db->select('r.*, u1.full_name as creator_name, u2.full_name as approver_name')
            ->from('procedure_history_revisions r')
            ->join('users u1', 'u1.id_user = r.created_by', 'left')
            ->join('users u2', 'u2.id_user = r.approved_by', 'left')
            ->where('r.procedure_id', $procedureId)
            ->order_by('r.revision_no', 'ASC')
            ->get()
            ->result();

        // Auto-backfill for existing published procedure if missing from procedure_history_revisions
        if (empty($revisions)) {
            $procedure = $this->getProcedureById($procedureId);
            if ($procedure && (in_array($procedure->status, ['PUB', 'APV']) || !empty($procedure->approved_at))) {
                $revNo = (int)(isset($procedure->revision) ? $procedure->revision : 0);
                $companyId = isset($procedure->company_id) ? $procedure->company_id : (isset($this->company) ? $this->company : 1);
                $fileName = "procedure_{$procedureId}.pdf";
                $filePath = "directory/PROCEDURES_PDF/{$companyId}/{$fileName}";
                $createdBy = !empty($procedure->prepared_by) ? $procedure->prepared_by : (!empty($procedure->created_by) ? $procedure->created_by : 'ADMIN');
                $approvedBy = !empty($procedure->approved_by) ? $procedure->approved_by : $createdBy;
                $approvedAt = !empty($procedure->approved_at) ? $procedure->approved_at : (!empty($procedure->created_at) ? $procedure->created_at : date('Y-m-d H:i:s'));
                $createdAt = !empty($procedure->created_at) ? $procedure->created_at : date('Y-m-d H:i:s');
                $desc = "Initial Published Document (Revisi {$revNo})";

                $revRecord = [
                    'procedure_id' => $procedureId,
                    'revision_no'  => $revNo,
                    'description'  => $desc,
                    'file_name'    => $fileName,
                    'file_path'    => $filePath,
                    'created_by'   => $createdBy,
                    'approved_by'  => $approvedBy,
                    'approved_at'  => $approvedAt,
                    'created_at'   => $createdAt,
                ];
                $this->saveProcedureRevision($revRecord);

                $revisions = $this->db->select('r.*, u1.full_name as creator_name, u2.full_name as approver_name')
                    ->from('procedure_history_revisions r')
                    ->join('users u1', 'u1.id_user = r.created_by', 'left')
                    ->join('users u2', 'u2.id_user = r.approved_by', 'left')
                    ->where('r.procedure_id', $procedureId)
                    ->order_by('r.revision_no', 'ASC')
                    ->get()
                    ->result();
            }
        }

        return $revisions;
    }

    public function saveProcedureRevision($data)
    {
        if (!$this->db->table_exists('procedure_history_revisions')) {
            $this->_createProcedureHistoryRevisionsTable();
        }
        return $this->db->insert('procedure_history_revisions', $data);
    }

    private function _createProcedureHistoryRevisionsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `procedure_history_revisions` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `procedure_id` VARCHAR(50) NOT NULL,
          `revision_no` INT NOT NULL DEFAULT 0,
          `description` TEXT NULL,
          `file_name` VARCHAR(255) NULL,
          `file_path` VARCHAR(255) NULL,
          `created_by` VARCHAR(50) NOT NULL,
          `approved_by` VARCHAR(50) NULL,
          `approved_at` DATETIME NULL,
          `created_at` DATETIME NOT NULL,
          INDEX (`procedure_id`),
          INDEX (`revision_no`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        return $this->db->query($sql);
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
