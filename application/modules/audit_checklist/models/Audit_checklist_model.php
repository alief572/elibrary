<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Audit_checklist_model extends BF_Model
{
    protected $table_name = 'audit_checklist';
    protected $key        = 'id';
    protected $set_created = false;
    protected $set_modified = false;
    protected $soft_deletes = false;
    protected $date_format = 'datetime';
    protected $log_user = true;

    public function __construct()
    {
        parent::__construct();
    }

    /* ID GENERATION */

    public function generateChecklistId()
    {
        $count    = 1;
        $result   = $this->db->select('MAX(RIGHT(id,3)) as id')->from('audit_checklist')->where(['SUBSTR(id,3,4)' => date('ym')])->get()->row();

        if ($result && $result->id > 0) {
            $count = $result->id + 1;
        }
        return "CK" . date('ym-') . sprintf("%03d", $count);
    }

    public function getNextChecklistDetailId($id)
    {
        $count    = 1;
        $result   = $this->db->select('MAX(RIGHT(id,3)) as id')->from('audit_checklist_details')->where(['checklist_id' => $id])->get()->row();

        if ($result && $result->id > 0) {
            $count = $result->id + 1;
        }
        return $count;
    }

    public function generateAuditId()
    {
        $count    = 1;
        $result   = $this->db->select('MAX(RIGHT(id,3)) as id')->from('audit_checklist_audit')->where(['SUBSTR(id,3,4)' => date('ym')])->get()->row();

        if ($result && $result->id > 0) {
            $count = $result->id + 1;
        }
        return "AT" . date('ym-') . sprintf("%03d", $count);
    }

    public function getNextAuditDetailId($id)
    {
        $count    = 1;
        $result   = $this->db->select('MAX(RIGHT(id,3)) as id')->from('audit_checklist_audit_details')->where(['audit_id' => $id])->get()->row();

        if ($result && $result->id > 0) {
            $count = $result->id + 1;
        }
        return $count;
    }

    public function getNextAuditDetailId2($id)
    {
        $count    = 1;
        $result   = $this->db->select('MAX(RIGHT(id,3)) as id')->from('audit_non_checklist_audit_details')->where(['audit_id' => $id])->get()->row();

        if ($result && $result->id > 0) {
            $count = $result->id + 1;
        }
        return $count;
    }

    /* FETCH METHODS */

    public function getActiveChecklists()
    {
        return $this->db->get_where('view_audit_checklist', ['status' => '1'])->result();
    }

    public function getChecklistById($id)
    {
        return $this->db->get_where('audit_checklist', ['id' => $id, 'status' => '1'])->row();
    }

    public function getChecklistByViewId($id)
    {
        return $this->db->get_where('view_audit_checklist', ['id' => $id, 'status' => '1'])->row();
    }

    public function getChecklistDetails($checklistId)
    {
        return $this->db->get_where('audit_checklist_details', ['checklist_id' => $checklistId, 'status' => '1'])->result();
    }

    public function getProcedures($companyId)
    {
        return $this->db->get_where('procedures', [
            'company_id' => $companyId,
            'status !=' => 'DEL',
            'deleted_at' => null
        ])->result();
    }

    public function getCrossReferences($procedureId, $companyId)
    {
        $this->db->select('*')->from('view_cross_reference_details');
        $this->db->where("find_in_set($procedureId, procedure_id)");
        $this->db->where("company_id", $companyId);
        return $this->db->get()->result();
    }

    public function getRequirementDetailById($id)
    {
        return $this->db->get_where('requirement_details', ['id' => $id])->row();
    }

    public function getAuditResults()
    {
        return $this->db->get_where('view_audit_checklist_audit', ['status' => '1'])->result();
    }

    public function getAuditDetailsAll()
    {
        return $this->db->get_where('audit_checklist_audit_details', ['status' => '1'])->result();
    }

    public function getAuditByViewId($id)
    {
        return $this->db->get_where('view_audit_checklist_audit', ['id' => $id, 'status' => '1'])->row();
    }

    public function getAuditDetails($auditId)
    {
        return $this->db->get_where('audit_checklist_audit_details', ['audit_id' => $auditId])->result();
    }

    public function getNonChecklistAuditDetails($auditId)
    {
        return $this->db->get_where('audit_non_checklist_audit_details', ['audit_id' => $auditId, 'status' => '1'])->result();
    }

    public function getAllCrossReferences($companyId)
    {
        return $this->db->get_where('view_cross_reference_details', ['company_id' => $companyId])->result();
    }

    public function getChaptersByProcedure($procedureId, $standardId, $companyId)
    {
        $this->db->select('*')->from('view_cross_reference_details');
        $this->db->where("find_in_set($procedureId, procedure_id)");
        $this->db->where(["requirement_id" => $standardId, "company_id" => $companyId]);
        return $this->db->get()->result();
    }

    public function getUsers($companyId)
    {
        return $this->db->get_where('view_users', ['company_id' => $companyId, 'status' => 'ACT'])->result();
    }

    /* SAVE / DELETE METHODS */

    public function saveChecklist($data, $checklist, $userId)
    {
        $this->db->trans_begin();

        if (isset($data['id']) && $data['id']) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = $userId;
            $this->db->update('audit_checklist', $data, ['id' => $data['id']]);
        } else {
            $data['id']         = $this->generateChecklistId();
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $userId;
            $this->db->insert('audit_checklist', $data);
        }

        if ($checklist) {
            $dtlID = $this->getNextChecklistDetailId($data['id']);
            foreach ($checklist as $ck) {
                if (isset($ck['id']) && $ck['id']) {
                    $ck['modified_at'] = date('Y-m-d H:i:s');
                    $ck['modified_by'] = $userId;
                    $this->db->update('audit_checklist_details', $ck, ['id' => $ck['id']]);
                } else {
                    $ck['id']         = $data['id'] . sprintf("%03d", $dtlID++);
                    $ck['checklist_id'] = $data['id'];
                    $ck['created_at'] = date('Y-m-d H:i:s');
                    $ck['created_by'] = $userId;
                    $this->db->insert('audit_checklist_details', $ck);
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    public function saveAudit($data, $detail, $temuan, $userId)
    {
        $this->db->trans_begin();

        if (isset($data['id']) && $data['id']) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = $userId;
            $this->db->update('audit_checklist_audit', $data, ['id' => $data['id']]);
        } else {
            $data['id']         = $this->generateAuditId();
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $userId;
            $this->db->insert('audit_checklist_audit', $data);
        }

        if ($detail) {
            $dtlID = $this->getNextAuditDetailId($data['id']);
            foreach ($detail as $ck) {
                if (isset($ck['id']) && $ck['id']) {
                    $ck['modified_at'] = date('Y-m-d H:i:s');
                    $ck['modified_by'] = $userId;
                    $this->db->update('audit_checklist_audit_details', $ck, ['id' => $ck['id']]);
                } else {
                    $ck['id']         = $data['id'] . sprintf("%03d", $dtlID++);
                    $ck['audit_id'] = $data['id'];
                    $ck['created_at'] = date('Y-m-d H:i:s');
                    $ck['created_by'] = $userId;
                    $this->db->insert('audit_checklist_audit_details', $ck);
                }
            }
        }

        if ($temuan) {
            $dtlID = $this->getNextAuditDetailId2($data['id']);
            foreach ($temuan as $v) {
                if (isset($v['id']) && $v['id']) {
                    $v['modified_at']   = date('Y-m-d H:i:s');
                    $v['modified_by']   = $userId;
                    $this->db->update('audit_non_checklist_audit_details', $v, ['id' => $v['id']]);
                } else {
                    $v['id']            = $data['id'] . "-" . sprintf("%03d", $dtlID++);
                    $v['audit_id']      = $data['id'];
                    $v['created_at']    = date('Y-m-d H:i:s');
                    $v['created_by']    = $userId;
                    $this->db->insert('audit_non_checklist_audit_details', $v);
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    public function deleteChecklist($id)
    {
        $this->db->trans_begin();
        $this->db->update('audit_checklist', ['status' => '0'], ['id' => $id]);
        $this->db->update('audit_checklist_audit', ['status' => '0'], ['checklist_id' => $id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    public function deleteChecklistDetail($id)
    {
        return $this->db->update('audit_checklist_details', ['status' => '0'], ['id' => $id]);
    }

    public function deleteAudit($id)
    {
        return $this->db->update('audit_checklist_audit', ['status' => '0'], ['id' => $id]);
    }

    public function deleteNonChecklistAudit($id)
    {
        return $this->db->update('audit_non_checklist_audit_details', ['status' => '0'], ['id' => $id]);
    }

    public function updateAuditDetailFile($id, $fileData)
    {
        return $this->db->update('audit_checklist_audit_details', $fileData, ['id' => $id]);
    }
}
