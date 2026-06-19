<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Documents_list_model extends BF_Model
{
    protected $table_name = 'directory';
    protected $key        = 'id';
    protected $created_field = 'created_on';
    protected $modified_field = 'modified_on';
    protected $set_created = true;
    protected $set_modified = true;
    protected $soft_deletes = true;
    protected $date_format = 'datetime';
    protected $log_user = true;

    public function __construct()
    {
        parent::__construct();
    }

    /* DIRECTORY / FILE OPERATIONS */

    public function getMainData()
    {
        return $this->db->get_where('directory', ['parent_id' => '0'])->result();
    }

    public function getDirectoryById($id)
    {
        return $this->db->get_where('directory', ['id' => $id])->row();
    }

    public function getDirectoryByIdArray($id)
    {
        return $this->db->get_where('directory', ['id' => $id])->row_array();
    }

    public function getSubFolders($parentId, $companyId)
    {
        return $this->db->get_where('directory', [
            'parent_id' => $parentId,
            'flag_type' => 'FOLDER',
            'status !=' => 'DEL',
            'company_id' => $companyId
        ])->result();
    }

    public function getSubFiles($parentId, $companyId)
    {
        return $this->db->get_where('directory', [
            'parent_id' => $parentId,
            'flag_type' => 'FILE',
            'status !=' => 'DEL',
            'company_id' => $companyId
        ])->result();
    }

    public function getAllFolders($companyId)
    {
        return $this->db->get_where('directory', [
            'flag_type' => 'FOLDER',
            'status !=' => 'DEL',
            'company_id' => $companyId
        ])->result();
    }

    public function getAllPublishedFiles($companyId)
    {
        return $this->db->get_where('directory', [
            'flag_type' => 'FILE',
            'status' => 'PUB',
            'status !=' => 'DEL',
            'company_id' => $companyId
        ])->result();
    }

    public function getAllLinks($companyId)
    {
        return $this->db->get_where('directory', [
            'flag_type' => 'LINK',
            'status !=' => 'DEL',
            'company_id' => $companyId
        ])->result();
    }

    public function getHistory($directoryId)
    {
        return $this->db->order_by('updated_at', 'ASC')
            ->get_where('directory_log', ['directory_id' => $directoryId])
            ->result();
    }

    /* PROCEDURES, FORMS, GUIDES, RECORDS */

    public function getProcedureGroups()
    {
        return $this->db->get_where('group_procedure', ['status' => 'ACT'])->result();
    }

    public function getPublishedProcedures($companyId)
    {
        return $this->db->where('company_id', $companyId)
            ->where('deleted_by', null)
            ->where_in('status', ['PUB', 'RVI', 'REV', 'COR', 'APV'])
            ->get('view_procedures')
            ->result_array();
    }

    public function getProcedureById($id)
    {
        return $this->db->get_where('view_procedures', ['id' => $id])->row();
    }

    public function getProcedureResult($id)
    {
        return $this->db->get_where('view_procedures', ['id' => $id])->result();
    }

    public function getProcedureDetails($procedureId)
    {
        return $this->db->order_by("CAST(number AS UNSIGNED)", "ASC")
            ->get_where('procedure_details', ['procedure_id' => $procedureId, 'status' => '1'])
            ->result();
    }

    public function getFormsByProcedure($procedureId, $activeOnly = true)
    {
        $where = ['procedure_id' => $procedureId];
        if ($activeOnly) {
            $where['active'] = 'Y';
            $where['status !='] = 'DEL';
        }
        return $this->db->order_by('name', 'ASC')->get_where('dir_forms', $where)->result();
    }

    public function getGuidesByProcedure($procedureId, $activeOnly = true)
    {
        $where = ['procedure_id' => $procedureId];
        if ($activeOnly) {
            $where['active'] = 'Y';
            $where['status !='] = 'DEL';
        }
        return $this->db->order_by('name', 'ASC')->get_where('dir_guides', $where)->result();
    }

    public function getRecordsByProcedure($procedureId, $companyId)
    {
        return $this->db->order_by('name', 'ASC')->get_where('dir_records', [
            'procedure_id' => $procedureId,
            'status !=' => 'DEL',
            'flag_type' => 'FOLDER',
            'company_id' => $companyId,
            'parent_id' => null
        ])->result();
    }

    public function countRecords($procedureId, $companyId)
    {
        return $this->db->get_where('dir_records', [
            'procedure_id' => $procedureId,
            'status' => 'PUB',
            'flag_type' => 'FILE',
            'company_id' => $companyId
        ])->num_rows();
    }

    public function getRecordById($id)
    {
        return $this->db->get_where('dir_records', ['id' => $id])->row();
    }

    public function getFormById($id)
    {
        return $this->db->get_where('dir_forms', ['id' => $id])->row();
    }

    public function getGuideById($id)
    {
        return $this->db->get_where('dir_guides', ['id' => $id])->row();
    }

    public function getRecordsFiltered($where)
    {
        // Ensure deleted records are excluded
        $where['status !='] = 'DEL';
        // Apply default alphabetical sorting
        return $this->db->order_by('name', 'ASC')->get_where('dir_records', $where)->result();
    }

    /* MATERI TRAINING */

    public function getMateri($companyId)
    {
        return $this->db->get_where('materi', ['status' => '1', 'company_id' => $companyId])->result();
    }

    public function getMateriDetails($companyId)
    {
        return $this->db->get_where('materi_details', ['status' => '1', 'company_id' => $companyId])->result();
    }

    public function getMateriById($id)
    {
        return $this->db->get_where('materi_details', ['id' => $id])->result();
    }

    public function getMateriData($detailId)
    {
        return $this->db->get_where('materi_detail_data', ['materi_detail_id' => $detailId, 'status' => '1'])->result();
    }

    public function getMateriFile($id)
    {
        return $this->db->get_where('materi_detail_data', ['id' => $id])->row();
    }

    /* GUIDES (IK) */

    public function getGuidesIK($companyId)
    {
        return $this->db->get_where('guides', ['status' => '1', 'company_id' => $companyId])->result();
    }

    public function getGuideDetailsIK($companyId)
    {
        return $this->db->get_where('guide_details', ['status' => '1', 'company_id' => $companyId])->result();
    }

    public function getGuideDetailByIdIK($id, $companyId)
    {
        return $this->db->get_where('guide_details', ['id' => $id, 'company_id' => $companyId])->result();
    }

    public function getGuideDetailDataIK($detailId, $companyId)
    {
        return $this->db->get_where('view_guides_detail_data', ['guide_detail_id' => $detailId, 'company_id' => $companyId])->result();
    }

    public function getGuideDocuments($detailDataId)
    {
        return $this->db->get_where('guide_documents', ['guide_detail_data_id' => $detailDataId, 'status' => '1'])->result();
    }

    public function getGuideVideoData($id)
    {
        return $this->db->get_where('view_guides_detail_data', ['id' => $id])->row();
    }

    public function getGuideDetailDataByIdIK($id)
    {
        return $this->db->get_where('guide_detail_data', ['id' => $id])->row();
    }

    public function getGuideDocumentById($id)
    {
        return $this->db->get_where('guide_documents', ['id' => $id])->row();
    }

    /* COMPLIANCE & CROSS REFERENCE */

    public function getAllReferences()
    {
        return $this->db->get_where('view_references')->result();
    }

    public function getComplianceReview($referenceId)
    {
        return $this->db->order_by('last_review', 'DESC')
            ->get_where('compilation_reviews', ['reference_id' => $referenceId])
            ->row();
    }

    public function getCrossReferences($companyId)
    {
        return $this->db->get_where('view_cross_references', ['company_id' => $companyId])->result();
    }

    public function getCrossReferenceById($id, $companyId)
    {
        return $this->db->get_where('view_cross_references', ['company_id' => $companyId, 'id' => $id])->row();
    }

    public function getRequirementDetails($requirementId)
    {
        return $this->db->get_where('requirement_details', ['requirement_id' => $requirementId])->result();
    }

    public function getCrossReferenceDetails($referenceId)
    {
        return $this->db->get_where('view_cross_reference_details', ['reference_id' => $referenceId])->result_array();
    }

    public function getRequirementById($id, $companyId)
    {
        return $this->db->get_where('requirements', ['company_id' => $companyId, 'id' => $id])->row();
    }

    public function getCrossReferenceByProcedureAndRequirement($procedureId, $requirementId, $companyId)
    {
        $this->db->select('chapter,procedure_id,requirement_id')->from('view_cross_reference_details');
        $this->db->where("find_in_set($procedureId, procedure_id)");
        $this->db->where("company_id", $companyId);
        $this->db->where("requirement_id", $requirementId);
        return $this->db->get()->result();
    }

    /* OTHERS */

    public function getActiveUsers($companyId = null)
    {
        $where = ['status' => 'ACT'];
        if ($companyId) {
            $where['company_id'] = $companyId;
            $where['id_user !='] = '1';
        }
        return $this->db->get_where('view_users', $where)->result();
    }

    public function getPositions()
    {
        return $this->db->get('positions')->result();
    }

    public function getUsers()
    {
        return $this->db->get_where('users', ['status' => 'ACT'])->result();
    }
}
