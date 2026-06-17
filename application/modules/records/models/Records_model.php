<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Records_model extends BF_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /* FETCH METHODS */

    public function getActiveProcedures($companyId)
    {
        return $this->db->get_where('procedures', [
            'company_id' => $companyId,
            'deleted_at' => null,
            'status !=' => 'DEL'
        ])->result();
    }

    public function getProcedureById($id, $companyId)
    {
        return $this->db->get_where('procedures', ['company_id' => $companyId, 'id' => $id])->row();
    }

    public function getProcedureByStatus($id, $companyId, $status)
    {
        return $this->db->get_where('procedures', ['id' => $id, 'company_id' => $companyId, 'status' => $status])->row();
    }

    public function getProcedureDetails($procedureId)
    {
        return $this->db->order_by('number', 'asc')->get_where('procedure_details', ['procedure_id' => $procedureId, 'status' => '1'])->result();
    }

    public function getProcedureGroups()
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

    public function getFormsByProcedure($procedureId)
    {
        return $this->db->get_where('dir_forms', ['procedure_id' => $procedureId, 'status !=' => 'DEL'])->result();
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

    public function getGuidesByProcedure($procedureId)
    {
        return $this->db->get_where('dir_guides', ['procedure_id' => $procedureId, 'status !=' => 'DEL'])->result();
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

    public function getRecordsByProcedure($procedureId)
    {
        return $this->db->get_where('dir_records', [
            'procedure_id' => $procedureId,
            'status !=' => 'DEL',
            'flag_type' => 'FOLDER',
            'parent_id' => null
        ])->result();
    }

    public function getDirectoryLog($id)
    {
        return $this->db->order_by('updated_at', 'ASC')->get_where('view_directory_log', ['directory_id' => $id])->result();
    }

    public function getSubRecords($procedureId, $parentId)
    {
        return $this->db->get_where('dir_records', [
            'procedure_id' => $procedureId,
            'parent_id'    => $parentId,
            'status !='    => 'DEL'
        ])->result();
    }

    public function searchRecords($procedureId, $keyword)
    {
        $this->db->where('procedure_id', $procedureId);
        $this->db->where('status !=', 'DEL');
        $this->db->like('name', $keyword);
        return $this->db->get('dir_records')->result();
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
                'directory_id' => $thisData->id,
                'new_status'   => $thisData->status,
                'doc_type'     => 'Procedure',
                'note'         => 'New input data procedure',
                'updated_by'   => $userId
            ]);
        }

        if ($dataFlow) {
            $dataFlow['procedure_id'] = $proId;
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
        $data = array_merge([
            'modified_by' => $userId,
            'modified_at' => date('Y-m-d H:i:s'),
            'status'      => $status
        ], $extra);
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

    public function deleteFile($table, $id, $userId)
    {
        $data = [
            'status'     => 'DEL',
            'deleted_by' => $userId,
            'deleted_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->update($table, $data, ['id' => $id]);
    }

    public function getFileName($table, $id)
    {
        $row = $this->db->get_where($table, ['id' => $id])->row();
        return $row ? $row->file_name : null;
    }

    /* CROSS REFERENCE */

    public function getCrossReferences($procedureId, $companyId)
    {
        $this->db->select('*')->from('view_cross_reference_details');
        $this->db->where("find_in_set($procedureId, procedure_id)");
        $this->db->where("company_id", $companyId);
        return $this->db->get()->result();
    }

    public function getFileById($table, $id)
    {
        return $this->db->get_where($table, ['id' => $id])->row();
    }

    public function saveFolder($data, $userId, $companyId)
    {
        $data['company_id'] = $companyId;
        $data['flag_type']  = 'FOLDER';

        $check = $this->db->get_where('dir_records', ['id' => $data['id']])->num_rows();
        if (intval($check) == 0) {
            $data['created_by'] = $userId;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['status']     = 'PUB';
            $res = $this->db->insert('dir_records', $data);
        } else {
            $data['modified_by'] = $userId;
            $data['modified_at'] = date('Y-m-d H:i:s');
            $res = $this->db->update('dir_records', $data, ['id' => $data['id']]);
        }
        return $res;
    }

    public function getAllFolders($procedureId)
    {
        return $this->db->get_where('dir_records', [
            'procedure_id' => $procedureId,
            'flag_type'    => 'FOLDER',
            'status !='    => 'DEL'
        ])->result();
    }

    public function getFolderHierarchy($procedureId, $parentId = null, $level = 0)
    {
        $this->db->where('procedure_id', $procedureId);
        $this->db->where('flag_type', 'FOLDER');
        $this->db->where('status !=', 'DEL');
        if ($parentId === null) {
            $this->db->where('parent_id IS NULL', null, false);
        } else {
            $this->db->where('parent_id', $parentId);
        }
        $folders = $this->db->get('dir_records')->result();

        $hierarchy = [];
        foreach ($folders as $folder) {
            $folder->level = $level;
            $hierarchy[] = $folder;
            $children = $this->getFolderHierarchy($procedureId, $folder->id, $level + 1);
            $hierarchy = array_merge($hierarchy, $children);
        }
        return $hierarchy;
    }

    public function moveRecord($recordId, $targetFolderId, $userId)
    {
        $data = [
            'parent_id'   => ($targetFolderId == 'root') ? null : $targetFolderId,
            'modified_by' => $userId,
            'modified_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->update('dir_records', $data, ['id' => $recordId]);
    }

    public function getDescendants($folderId)
    {
        $descendants = [];
        $children = $this->db->get_where('dir_records', [
            'parent_id' => $folderId,
            'flag_type' => 'FOLDER',
            'status !=' => 'DEL'
        ])->result();

        foreach ($children as $child) {
            $descendants[] = $child->id;
            $descendants = array_merge($descendants, $this->getDescendants($child->id));
        }
        return $descendants;
    }
}
