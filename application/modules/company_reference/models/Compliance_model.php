<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Compliance_model
 *
 * Bertanggung jawab atas semua operasi data terkait compliance_subject
 * dan tabel subjects. Dipisahkan dari Company_reference_model karena
 * merupakan domain yang berbeda (kepatuhan vs referensi perusahaan).
 */

class Compliance_model extends BF_Model
{
  protected $table_name    = 'compliance_subject';
  protected $key           = 'id';
  protected $set_created   = false;
  protected $set_modified  = false;
  protected $soft_deletes  = false;
  protected $date_format   = 'datetime';
  protected $log_user      = false;

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Ambil compliance subjects yang sudah terdaftar
   * untuk sebuah perusahaan dan branch tertentu.
   *
   * @param  int       $companyId
   * @param  int|null  $branchId
   * @return array
   */
  public function getExistingSubjects($companyId, $referenceId, $branchId = null)
  {
    $where = [
      'company_id' => $companyId,
      'reference_id' => $referenceId,
    ];

    if ($branchId) {
      $where['branch_id'] = $branchId;
    }
    return $this->db->get_where('compliance_subject', $where)->result_array();
  }

  /**
   * Ambil semua master data subjects.
   *
   * @return array
   */
  public function getAllSubjects()
  {
    return $this->db->get('subjects')->result();
  }

  /**
   * Simpan satu compliance subject baru.
   * Menjalankan transaksi dan mengembalikan array response standar.
   *
   * @param  int    $subjectId
   * @param  int    $companyId
   * @param  int    $userId
   * @return array  ['status' => 1|0, 'msg' => '...']
   */
  public function saveSubject($subjectId, $companyId, $userId, $referenceId, $branchId = null)
  {
    $data = [
      'subject_id' => $subjectId,
      'company_id' => $companyId,
      'branch_id'  => $branchId,
      'reference_id' => $referenceId,
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => $userId,
    ];

    $this->db->trans_begin();
    $this->db->insert('compliance_subject', $data);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return ['status' => 0, 'msg' => 'Data Chapter failed to save. Please try again.'];
    }

    $this->db->trans_commit();
    return ['status' => 1, 'msg' => 'Data Chapter successfully saved..'];
  }
}
