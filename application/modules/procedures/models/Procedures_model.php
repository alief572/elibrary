<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Procedures_model extends BF_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /* FETCH METHODS */

    public function getProceduresByStatus($companyId, $status)
    {
        return $this->db->get_where('procedures', [
            'company_id' => $companyId,
            'deleted_at' => null,
            'status' => $status
        ])->result();
    }

    /**
     * Get all procedures for a company (regardless of status, excluding deleted/invalid)
     */
    public function getAllProcedures($companyId)
    {
        return $this->db->where('company_id', $companyId)
            ->where('deleted_at', null)
            ->where_not_in('status', ['0', 'DEL', 'HLD'])
            ->order_by('name', 'ASC')
            ->get('procedures')
            ->result();
    }

    public function getProcedureLogs($docType, $status)
    {
        return $this->db->order_by('id', 'DESC')
            ->select('*')
            ->get_where('directory_log', ['doc_type' => $docType, 'new_status' => $status])
            ->result();
    }

    public function getProcedureById($id, $companyId = null)
    {
        if ($companyId) {
            return $this->db->get_where('procedures', ['company_id' => $companyId, 'id' => $id])->row();
        }
        return $this->db->get_where('procedures', ['id' => $id])->row();
    }

    public function getProcedureDetails($procedureId)
    {
        $results = $this->db->where('procedure_id', $procedureId)
            ->where('status', '1')
            ->get('procedure_details')
            ->result();

        // Natural sort by 'number' field (handles 4.1, 4.1.1, 4.1.1.a, 4.2, 5, etc.)
        usort($results, function ($a, $b) {
            return strnatcasecmp($a->number, $b->number);
        });

        return $results;
    }

    public function getProcedureGroups()
    {
        return $this->db->get('group_procedure')->result();
    }

    public function getActiveGroups()
    {
        return $this->db->get_where('group_procedure', ['status' => 'ACT'])->result();
    }

    public function getActiveUsers($companyId)
    {
        return $this->db->get_where('view_users', ['status' => 'ACT', 'id_user !=' => '1', 'company_id' => $companyId])->result();
    }

    public function getAllActiveUsers()
    {
        return $this->db->get_where('view_users', ['status' => 'ACT', 'id_user !=' => '1'])->result();
    }

    public function getPositions($companyId = null)
    {
        if ($companyId) {
            return $this->db->get_where('positions', ['company_id' => $companyId])->result();
        }
        return $this->db->get('positions')->result();
    }

    public function getCompany($companyId)
    {
        return $this->db->get_where('companies', ['id_perusahaan' => $companyId])->row();
    }

    public function getFormsByProcedure($procedureId, $statusNot = 'DEL')
    {
        $this->db->where('procedure_id', $procedureId);
        if ($statusNot) {
            $this->db->where('status !=', $statusNot);
        }
        return $this->db->get('dir_forms')->result();
    }

    /**
     * Get all forms across all procedures for a company
     */
    public function getAllForms($companyId)
    {
        return $this->db->select('dir_forms.*, procedures.name as procedure_name')
            ->from('dir_forms')
            ->join('procedures', 'procedures.id = dir_forms.procedure_id', 'left')
            ->where('dir_forms.company_id', $companyId)
            ->where('dir_forms.status !=', 'DEL')
            ->order_by('procedures.name', 'ASC')
            ->order_by('dir_forms.name', 'ASC')
            ->get()
            ->result();
    }

    public function getActiveFormsByProcedure($procedureId, $companyId)
    {
        return $this->db->get_where('dir_forms', [
            'procedure_id' => $procedureId,
            'company_id' => $companyId,
            'active' => 'Y',
            'status !=' => 'DEL'
        ])->result();
    }

    public function getGuidesByProcedure($procedureId, $statusNot = 'DEL')
    {
        $this->db->where('procedure_id', $procedureId);
        if ($statusNot) {
            $this->db->where('status !=', $statusNot);
        }
        return $this->db->get('dir_guides')->result();
    }

    /**
     * Get all guides (IK) across all procedures for a company
     */
    public function getAllGuides($companyId)
    {
        return $this->db->select('dir_guides.*, procedures.name as procedure_name')
            ->from('dir_guides')
            ->join('procedures', 'procedures.id = dir_guides.procedure_id', 'left')
            ->where('dir_guides.company_id', $companyId)
            ->where('dir_guides.status !=', 'DEL')
            ->order_by('procedures.name', 'ASC')
            ->order_by('dir_guides.name', 'ASC')
            ->get()
            ->result();
    }

    public function getActiveGuidesByProcedure($procedureId, $companyId)
    {
        return $this->db->get_where('dir_guides', [
            'procedure_id' => $procedureId,
            'company_id' => $companyId,
            'active' => 'Y',
            'status !=' => 'DEL'
        ])->result();
    }

    public function getRecordsByProcedure($procedureId, $statusNot = 'DEL')
    {
        return $this->db->get_where('dir_records', [
            'procedure_id' => $procedureId,
            'status !=' => $statusNot,
            'flag_type' => 'FOLDER',
            'parent_id' => null
        ])->result();
    }

    public function getSubRecords($procedureId, $parentId, $statusNot = 'DEL')
    {
        return $this->db->get_where('dir_records', [
            'procedure_id' => $procedureId,
            'parent_id' => $parentId,
            'status !=' => $statusNot
        ])->result();
    }

    public function getDirectoryLog($id)
    {
        return $this->db->order_by('updated_at', 'ASC')->get_where('view_directory_log', ['directory_id' => $id])->result();
    }

    public function getCrossReferences($procedureId, $companyId)
    {
        $this->db->select('*')->from('view_cross_reference_details');
        $this->db->where("find_in_set($procedureId, procedure_id)");
        $this->db->where("company_id", $companyId);
        return $this->db->get()->result();
    }

    /* SAVE / UPDATE METHODS */

    public function saveProcedure($data, $dataFlow, $userId)
    {
        $this->db->trans_begin();

        if (isset($data['id']) && $data['id']) {
            $data['modified_by'] = $userId;
            $data['modified_at'] = date('Y-m-d H:i:s');
            $proId = $data['id'];
            $this->db->update('procedures', $data, ['id' => $data['id']]);
        } else {
            $data['created_by'] = $userId;
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('procedures', $data);
            $proId = $this->db->insert_id();

            $thisData = $this->db->get_where('procedures', ['id' => $proId])->row();
            $this->updateHistory([
                'directory_id' => $proId,
                'new_status'   => $thisData->status,
                'doc_type'     => 'Procedure',
                'note'         => 'New input data procedure',
                'updated_by'   => $userId
            ]);
        }

        if ($dataFlow) {
            $dataFlow['procedure_id'] = $proId;
            $dataFlow['relate_doc']    = isset($dataFlow['relate_doc']) ? json_encode($dataFlow['relate_doc']) : '-';
            $dataFlow['relate_ik_doc'] = isset($dataFlow['relate_ik_doc']) ? json_encode($dataFlow['relate_ik_doc']) : '-';

            if (isset($dataFlow['id']) && $dataFlow['id']) {
                $dataFlow['modified_by'] = $userId;
                $dataFlow['modified_at'] = date('Y-m-d H:i:s');
                $this->db->update('procedure_details', $dataFlow, ['id' => $dataFlow['id']]);
            } else {
                $dataFlow['created_by'] = $userId;
                $dataFlow['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert('procedure_details', $dataFlow);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return $proId;
    }

    public function saveFlowDetail($data, $proId, $userId)
    {
        $data['procedure_id'] = $proId;
        $data['relate_doc']    = isset($data['relate_doc']) ? json_encode($data['relate_doc']) : '-';
        $data['relate_ik_doc'] = isset($data['relate_ik_doc']) ? json_encode($data['relate_ik_doc']) : '-';

        if (isset($data['id']) && $data['id']) {
            $data['modified_by'] = $userId;
            $data['modified_at'] = date('Y-m-d H:i:s');
            $res = $this->db->update('procedure_details', $data, ['id' => $data['id']]);
        } else {
            $data['created_by'] = $userId;
            $data['created_at'] = date('Y-m-d H:i:s');
            $res = $this->db->insert('procedure_details', $data);
        }
        return $res;
    }

    public function updateHistory($data)
    {
        if (!isset($data['updated_by'])) {
            $data['updated_by'] = $this->auth->user_id();
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('directory_log', $data);
    }

    public function deleteProcedure($id, $userId, $companyId)
    {
        $data = [
            'deleted_by' => $userId,
            'deleted_at' => date('Y-m-d H:i:s'),
            'status'     => 'DEL'
        ];
        return $this->db->update('procedures', $data, ['company_id' => $companyId, 'id' => $id]);
    }

    public function deleteFlow($id, $userId)
    {
        $data = [
            'deleted_by' => $userId,
            'deleted_at' => date('Y-m-d H:i:s'),
            'status'     => '0'
        ];
        return $this->db->update('procedure_details', $data, ['id' => $id]);
    }

    public function updateStatus($id, $status, $userId, $companyId, $extra = [])
    {
        $data = [
            'modified_by' => $userId,
            'modified_at' => date('Y-m-d H:i:s')
        ];
        if ($status !== null) {
            $data['status'] = $status;
        }
        $data = array_merge($data, $extra);
        return $this->db->update('procedures', $data, ['company_id' => $companyId, 'id' => $id]);
    }

    /* FILE OPERATIONS */

    public function saveFile($table, $data, $userId)
    {
        $check = $this->db->get_where($table, ['id' => $data['id']])->num_rows();
        if (intval($check) == 0) {
            $data['created_by'] = $userId;
            $data['created_at'] = date('Y-m-d H:i:s');
            $res = $this->db->insert($table, $data);
        } else {
            $data['modified_by'] = $userId;
            $data['modified_at'] = date('Y-m-d H:i:s');
            $res = $this->db->update($table, $data, ['id' => $data['id']]);
        }
        return $res;
    }

    public function saveFolder($data, $userId, $companyId)
    {
        $data['company_id'] = $companyId;
        if (isset($data['id']) && $data['id']) {
            $data['modified_by'] = $userId;
            $data['modified_at'] = date('Y-m-d H:i:s');
            return $this->db->update('dir_records', $data, ['id' => $data['id']]);
        } else {
            $data['id']         = uniqid(date('m'));
            $data['created_by'] = $userId;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['status']     = 'PUB';
            return $this->db->insert('dir_records', $data);
        }
    }

    public function deleteFile($table, $id, $userId)
    {
        $data = [
            'status'     => 'DEL',
            'deleted_by' => $userId,
            'deleted_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->update($table, $data, ['id' => $id]);
    }

    public function getFileById($table, $id)
    {
        return $this->db->get_where($table, ['id' => $id])->row();
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
            $procedure = $this->getProcedureById($procedureId, isset($this->company) ? $this->company : null);
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
}
