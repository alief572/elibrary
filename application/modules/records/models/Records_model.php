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
        $results = $this->db->get_where('procedure_details', ['procedure_id' => $procedureId, 'status' => '1'])->result();

        usort($results, function ($a, $b) {
            return strnatcasecmp($a->number, $b->number);
        });

        return $results;
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
        return $this->db
            ->order_by("(CASE WHEN flag_type = 'FOLDER' THEN 0 ELSE 1 END)", 'ASC', false)
            ->order_by('name', 'ASC')
            ->get_where('dir_records', [
                'procedure_id' => $procedureId,
                'status !='    => 'DEL',
                'parent_id'    => null
            ])->result();
    }

    public function getDirectoryLog($id)
    {
        return $this->db->order_by('updated_at', 'ASC')->get_where('view_directory_log', ['directory_id' => $id])->result();
    }

    public function getSubRecords($procedureId, $parentId)
    {
        return $this->db
            ->order_by("(CASE WHEN flag_type = 'FOLDER' THEN 0 ELSE 1 END)", 'ASC', false)
            ->order_by('name', 'ASC')
            ->get_where('dir_records', [
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
        $this->db->order_by("(CASE WHEN flag_type = 'FOLDER' THEN 0 ELSE 1 END)", 'ASC', false);
        $this->db->order_by('name', 'ASC');
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
            if (isset($data['parent_id'])) {
                $data['parent_id'] = !empty($data['parent_id']) ? $data['parent_id'] : null;
            }
            $res = $this->db->insert($table, $data);
            // NEVER overwrite parent_id on update, preserving folder hierarchy (moving is handled via move/paste)
            unset($data['parent_id']);
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
            $data['parent_id']  = !empty($data['parent_id']) ? $data['parent_id'] : null;
            $res = $this->db->insert('dir_records', $data);
        } else {
            // NEVER overwrite parent_id when editing existing folder, preserving its folder hierarchy
            unset($data['parent_id']);
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

    public function deleteDescendants($folderId, $userId)
    {
        $children = $this->db->get_where('dir_records', ['parent_id' => $folderId, 'status !=' => 'DEL'])->result();
        foreach ($children as $child) {
            $data = [
                'status'     => 'DEL',
                'deleted_by' => $userId,
                'deleted_at' => date('Y-m-d H:i:s')
            ];
            $this->db->update('dir_records', $data, ['id' => $child->id]);
            if ($child->flag_type == 'FOLDER') {
                $this->deleteDescendants($child->id, $userId);
            }
        }
    }

    public function batchMoveRecords($recordIds, $targetFolderId, $userId)
    {
        if (empty($recordIds) || !is_array($recordIds)) {
            return ['status' => 0, 'msg' => 'No items selected to move.'];
        }

        $targetParentId = ($targetFolderId === 'root') ? null : $targetFolderId;

        if ($targetParentId !== null) {
            $targetFolder = $this->getFileById('dir_records', $targetParentId);
            if (!$targetFolder || $targetFolder->status == 'DEL' || $targetFolder->flag_type != 'FOLDER') {
                return ['status' => 0, 'msg' => 'Target folder is invalid or does not exist.'];
            }
        }

        $movedCount = 0;
        $errors = [];

        foreach ($recordIds as $id) {
            $record = $this->getFileById('dir_records', $id);
            if (!$record || $record->status == 'DEL') {
                continue;
            }

            if ($record->id == $targetParentId) {
                $errors[] = "Cannot move \"{$record->name}\" into itself.";
                continue;
            }

            if ($record->flag_type == 'FOLDER' && $targetParentId !== null) {
                $descendants = $this->getDescendants($record->id);
                if (in_array($targetParentId, $descendants)) {
                    $errors[] = "Cannot move folder \"{$record->name}\" into one of its subfolders.";
                    continue;
                }
            }

            if ($record->parent_id == $targetParentId) {
                continue;
            }

            $success = $this->moveRecord($record->id, $targetFolderId, $userId);
            if ($success) {
                $movedCount++;
                $this->updateHistory([
                    'directory_id' => $record->id,
                    'new_status'   => $record->status,
                    'doc_type'     => ($record->flag_type == 'FOLDER' ? 'Folder' : 'Record'),
                    'note'         => 'Move ' . ($record->flag_type == 'FOLDER' ? 'Folder' : 'File') . ' to ' . ($targetFolderId == 'root' ? 'Root' : 'another folder')
                ]);
            }
        }

        if ($movedCount == 0 && !empty($errors)) {
            return ['status' => 0, 'msg' => implode("<br>", $errors)];
        }

        $msg = "Successfully moved $movedCount item(s).";
        if (!empty($errors)) {
            $msg .= "<br>Some items could not be moved:<br>" . implode("<br>", $errors);
        }

        return ['status' => 1, 'msg' => $msg, 'moved_count' => $movedCount];
    }

    public function copyRecord($recordId, $targetParentId, $userId, $companyId)
    {
        $record = $this->getFileById('dir_records', $recordId);
        if (!$record || $record->status == 'DEL') {
            return false;
        }

        $parentId = ($targetParentId === 'root') ? null : $targetParentId;

        // Snapshot children BEFORE insert to prevent infinite loop on self/descendant copy
        $children = [];
        if ($record->flag_type == 'FOLDER') {
            $children = $this->db->get_where('dir_records', [
                'parent_id' => $recordId,
                'status !=' => 'DEL'
            ])->result();
        }

        // Generate unique 15-character ID
        $newId = uniqid(date('m'));
        while ($this->db->get_where('dir_records', ['id' => $newId])->num_rows() > 0) {
            usleep(2000);
            $newId = uniqid(date('m'));
        }

        // Duplicate physical file if FILE
        $newFileName = null;
        if ($record->flag_type == 'FILE' && !empty($record->file_name)) {
            $upload_path = "./directory/RECORDS/" . $companyId . "/";
            $orig_file = $upload_path . $record->file_name;
            if (file_exists($orig_file)) {
                $ext = pathinfo($record->file_name, PATHINFO_EXTENSION);
                $newFileName = md5(uniqid(rand(), true)) . ($ext ? '.' . $ext : '');
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0755, true);
                }
                copy($orig_file, $upload_path . $newFileName);
            } else {
                $newFileName = $record->file_name;
            }
        }

        $newName = $record->name;
        if ($record->parent_id == $parentId) {
            $newName = $record->name . ' - Copy';
        }

        $newData = [
            'id'                     => $newId,
            'parent_id'              => $parentId,
            'procedure_id'           => $record->procedure_id,
            'flag_type'              => $record->flag_type,
            'flag_confidential'      => $record->flag_confidential,
            'confidential_group_ids' => $record->confidential_group_ids,
            'name'                   => $newName,
            'description'            => $record->description,
            'doc'                    => $record->doc,
            'file_name'              => $newFileName,
            'link_url'               => $record->link_url,
            'ext'                    => $record->ext,
            'active'                 => $record->active,
            'size'                   => $record->size,
            'company_id'             => $companyId,
            'status'                 => 'PUB',
            'created_by'             => $userId,
            'created_at'             => date('Y-m-d H:i:s'),
            'modified_by'            => null,
            'modified_at'            => null,
            'number'                 => $record->number,
            'determination_date'     => $record->determination_date,
            'about'                  => $record->about,
            'doc_status'             => $record->doc_status,
            'publisher'              => $record->publisher
        ];

        $this->db->insert('dir_records', $newData);

        $this->updateHistory([
            'directory_id' => $newId,
            'new_status'   => 'PUB',
            'doc_type'     => ($record->flag_type == 'FOLDER' ? 'Folder' : 'Record'),
            'note'         => 'Copy ' . ($record->flag_type == 'FOLDER' ? 'Folder' : 'File') . ' from ' . $record->name
        ]);

        if (!empty($children)) {
            foreach ($children as $child) {
                $this->copyRecord($child->id, $newId, $userId, $companyId);
            }
        }

        return $newId;
    }

    public function batchCopyRecords($recordIds, $targetFolderId, $userId, $companyId)
    {
        if (empty($recordIds) || !is_array($recordIds)) {
            return ['status' => 0, 'msg' => 'No items selected to copy.'];
        }

        $targetParentId = ($targetFolderId === 'root') ? null : $targetFolderId;

        if ($targetParentId !== null) {
            $targetFolder = $this->getFileById('dir_records', $targetParentId);
            if (!$targetFolder || $targetFolder->status == 'DEL' || $targetFolder->flag_type != 'FOLDER') {
                return ['status' => 0, 'msg' => 'Target folder is invalid or does not exist.'];
            }
        }

        $copiedCount = 0;
        foreach ($recordIds as $id) {
            $res = $this->copyRecord($id, $targetFolderId, $userId, $companyId);
            if ($res) {
                $copiedCount++;
            }
        }

        return ['status' => 1, 'msg' => "Successfully copied $copiedCount item(s).", 'copied_count' => $copiedCount];
    }
}
