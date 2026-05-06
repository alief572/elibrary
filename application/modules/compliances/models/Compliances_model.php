<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Compliances_model extends BF_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getReferences($companyId)
    {
        return $this->db->get_where('view_references', ['company_id' => $companyId])->result();
    }

    public function getReferenceById($id)
    {
        return $this->db->get_where('view_references', ['id' => $id])->row();
    }

    public function getCompliances($referenceId, $companyId)
    {
        return $this->db->get_where('view_compliances', ['reference_id' => $referenceId, 'company_id' => $companyId])->result();
    }

    public function getComplianceById($id)
    {
        return $this->db->get_where('view_compliances', ['id' => $id])->row();
    }

    public function getFileDataById($id)
    {
        return $this->db->get_where('compliance_details', ['id' => $id])->row();
    }

    public function getRegulationParagraphs($regulationId)
    {
        return $this->db->get_where('view_regulation_paragraphs', ['regulation_id' => $regulationId])->result();
    }

    public function getComplianceDetails($regulationId, $referenceId)
    {
        return $this->db->get_where('compliance_details', ['regulation_id' => $regulationId, 'reference_id' => $referenceId])->result();
    }

    public function getComplianceOpports($regulationId)
    {
        return $this->db->get_where('compliance_opports', ['regulation_id' => $regulationId])->result();
    }

    public function getComplianceSubjects($referenceId)
    {
        return $this->db->get_where('view_compliance_subjects', ['reference_id' => $referenceId])->result();
    }

    public function getAllComplianceSubjects($companyId)
    {
        return $this->db->get_where('view_compliance_subjects', ['company_id' => $companyId])->result();
    }

    public function getRefRegulations($referenceId)
    {
        return $this->db->get_where('view_ref_regulations', ['reference_id' => $referenceId])->result();
    }

    public function getRefRegulationsBySubject($subject)
    {
        return $this->db->get_where('view_ref_regulations', ['subject' => $subject])->result();
    }

    public function getCompilationReviews($referenceId)
    {
        return $this->db->get_where('compilation_reviews', ['reference_id' => $referenceId])->result();
    }

    public function getCompilationReviewsBySubject($subject)
    {
        return $this->db->get_where('compilation_reviews', ['subject' => $subject])->result();
    }

    public function getLatestReview($referenceId)
    {
        return $this->db->order_by('last_review', 'DESC')->get_where('compilation_reviews', ['reference_id' => $referenceId])->row();
    }

    public function getComplianceDetailsByReference($referenceId)
    {
        $query = $this->db->get_where('view_compliance_details', ['reference_id' => $referenceId]);
        return $query ? $query->result() : [];
    }

    public function getComplianceDetailsBySubject($subject)
    {
        $query = $this->db->get_where('view_compliance_details', ['subject' => $subject]);
        return $query ? $query->result() : [];
    }

    public function getComplianceDetailsFiltered($where)
    {
        if (isset($where['subject'])) {
            $this->db->select('vcd.*, rr.subject');
            $this->db->from('view_compliance_details vcd');
            $this->db->join('ref_regulations rr', 'vcd.regulation_id = rr.regulation_id AND vcd.reference_id = rr.reference_id', 'left');
            $this->db->where('rr.subject', $where['subject']);
            unset($where['subject']);

            if (!empty($where)) {
                foreach ($where as $k => $v) {
                    $this->db->where("vcd.$k", $v);
                }
            }
            $query = $this->db->get();
            return $query ? $query->result() : [];
        }

        $query = $this->db->get_where('view_compliance_details', $where);
        return $query ? $query->result() : [];
    }

    public function getCompOpports($referenceId)
    {
        $query = $this->db->get_where('view_comp_opports', ['reference_id' => $referenceId]);
        return $query ? $query->result() : [];
    }

    public function getActiveUsers($companyId)
    {
        return $this->db->get_where('view_users', ['company_id' => $companyId, 'status' => 'ACT'])->result();
    }

    public function getAllActiveUsers()
    {
        return $this->db->get_where('view_users', ['status' => 'ACT'])->result();
    }

    public function saveComplianceData($data, $detailComp, $detailOpport, $userId)
    {
        $this->db->trans_begin();

        if ($detailComp) {
            foreach ($detailComp as $dtlComp) {
                if ($dtlComp['id']) {
                    $dtlComp['modified_at'] = date('Y-m-d H:i:s');
                    $dtlComp['modified_by'] = $userId;
                    $this->db->update('compliance_details', $dtlComp, ['id' => $dtlComp['id']]);
                } else {
                    $dtlComp['created_at'] = date('Y-m-d H:i:s');
                    $dtlComp['created_by'] = $userId;
                    $this->db->insert('compliance_details', $dtlComp);
                }
            }
        }

        if ($detailOpport) {
            foreach ($detailOpport as $op) {
                if ($op['id']) {
                    $op['modified_at'] = date('Y-m-d H:i:s');
                    $op['modified_by'] = $userId;
                    $this->db->update('compliance_opports', $op, ['id' => $op['id']]);
                } else {
                    $op['created_at'] = date('Y-m-d H:i:s');
                    $op['created_by'] = $userId;
                    $this->db->insert('compliance_opports', $op);
                }
            }
        }

        // Calculate counts
        $ArrStatus = [];
        foreach ($data['detail'] as $dtl) {
            $ArrStatus[$dtl['status']][] = $dtl;
        }

        $CMP = (isset($ArrStatus['CMP'])) ? count($ArrStatus['CMP']) : 0;
        $NCM = (isset($ArrStatus['NCM'])) ? count($ArrStatus['NCM']) : 0;
        $NAP = (isset($ArrStatus['NAP'])) ? count($ArrStatus['NAP']) : 0;

        $this->db->update('ref_regulations', [
            'last_update'          => date('Y-m-d H:i:s'),
            'total_compliance'     => $CMP,
            'total_not_compliance' => $NCM,
            'total_not_applicable' => $NAP
        ], ['id' => $data['compliance_id']]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    public function updateComplianceFile($id, $fileName)
    {
        return $this->db->update('compliance_details', ['file' => $fileName], ['id' => $id]);
    }

    public function removeComplianceFile($id)
    {
        return $this->db->update('compliance_details', ['file' => null], ['id' => $id]);
    }

    public function insertReview($data)
    {
        return $this->db->insert('compilation_reviews', $data);
    }

    public function updateReferenceReview($id, $count, $lastReview, $userId)
    {
        return $this->db->update('references', [
            'counter_review' => $count,
            'last_review'    => $lastReview,
            'review_by'      => $userId,
        ], ['id' => $id]);
    }
}
