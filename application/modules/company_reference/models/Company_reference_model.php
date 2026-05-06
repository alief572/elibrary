<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Syamsudin
 * @copyright Copyright (c) 2021, Syamsudin
 *
 * Model untuk Company Reference.
 * Bertanggung jawab atas semua operasi data terkait references,
 * ref_standards, ref_regulations, dan tabel pendukungnya.
 */

class Company_reference_model extends BF_Model
{
  /** @var string User Table Name */
  protected $table_name     = 'company_reference';
  protected $key            = 'id';
  protected $created_field  = 'create_on';
  protected $modified_field = 'modified_on';
  protected $set_created    = true;
  protected $set_modified   = true;
  protected $soft_deletes   = false;
  protected $date_format    = 'datetime';
  protected $log_user       = true;

  public function __construct()
  {
    parent::__construct();
  }

  // -----------------------------------------------------------------------
  // READ METHODS
  // -----------------------------------------------------------------------

  /**
   * Ambil semua references berstatus OPN milik sebuah perusahaan.
   */
  public function getOpenByCompany($companyId)
  {
    return $this->db->get_where('view_references', [
      'status'     => 'OPN',
      'company_id' => $companyId,
    ])->result();
  }

  /**
   * Ambil semua references berstatus DONE.
   */
  public function getDoneAll()
  {
    return $this->db->get_where('view_references', ['status' => 'DONE'])->result();
  }

  /**
   * Ambil standards yang dikelompokkan berdasarkan reference_id.
   * Hanya mengambil data untuk reference_id yang diberikan.
   *
   * @param  array $refIds
   * @return array ['reference_id' => ['name1', 'name2', ...]]
   */
  public function getStandardsGrouped(array $refIds)
  {
    if (empty($refIds)) return [];

    $rows   = $this->db->where_in('reference_id', $refIds)->get('view_ref_standards')->result();
    $result = [];
    foreach ($rows as $row) {
      $result[$row->reference_id][] = $row->name;
    }
    return $result;
  }

  /**
   * Ambil regulations yang dikelompokkan berdasarkan reference_id.
   * Hanya mengambil data untuk reference_id yang diberikan.
   *
   * @param  array $refIds
   * @return array ['reference_id' => ['name1', 'name2', ...]]
   */
  public function getRegulationsGrouped(array $refIds)
  {
    if (empty($refIds)) return [];

    $rows   = $this->db->where_in('reference_id', $refIds)->get('view_ref_regulations')->result();
    $result = [];
    foreach ($rows as $row) {
      $result[$row->reference_id][] = $row->name;
    }
    return $result;
  }

  /**
   * Cari satu reference berdasarkan id, status OPN, dan branch.
   */
  public function findOpenById($id, $branchId)
  {
    return $this->db->get_where('view_references', [
      'id'        => $id,
      'status'    => 'OPN',
      'branch_id' => $branchId,
    ])->row();
  }

  /**
   * Cari satu reference berdasarkan id dan status OPN (tanpa filter branch).
   */
  public function findOpenByIdOnly($id)
  {
    return $this->db->get_where('view_references', [
      'id'     => $id,
      'status' => 'OPN',
    ])->row();
  }

  /**
   * Ambil daftar standards untuk sebuah reference.
   */
  public function getStandardsByRef($refId)
  {
    return $this->db->get_where('view_ref_standards', ['reference_id' => $refId])->result();
  }

  /**
   * Ambil daftar regulations untuk sebuah reference dan branch.
   */
  public function getRegsByRef($refId, $branchId)
  {
    return $this->db->get_where('view_ref_regulations', [
      'reference_id' => $refId,
      'branch_id'    => $branchId,
    ])->result();
  }

  /**
   * Ambil daftar regulations untuk sebuah reference (tanpa filter branch).
   */
  public function getRegsByRefOnly($refId)
  {
    return $this->db->get_where('view_ref_regulations', ['reference_id' => $refId])->result();
  }

  /**
   * Ambil semua data perusahaan.
   */
  public function getAllCompanies()
  {
    return $this->db->get('companies')->result();
  }

  /**
   * Ambil data perusahaan berdasarkan id.
   */
  public function getCompanyById($companyId)
  {
    return $this->db->get_where('companies', ['id_perusahaan' => $companyId])->result();
  }

  /**
   * Ambil daftar branch milik sebuah perusahaan.
   */
  public function getBranchesByCompany($companyId)
  {
    return $this->db->get_where('company_branch', ['company_id' => $companyId])->result();
  }

  /**
   * Ambil semua requirement yang aktif (status = 1).
   */
  public function getActiveRequirements()
  {
    return $this->db->get_where('requirements', ['status' => '1'])->result();
  }

  /**
   * Ambil regulations (subject-view) yang sudah published, untuk form edit.
   * Mengembalikan array yang sudah dikelompokkan: ['subject_id' => ['reg_id' => 'name']]
   */
  public function getRegulationsForDropdown()
  {
    $rows   = $this->db->get_where('view_regulation_subjects', ['status' => 'PUB'])->result();
    $result = [];
    foreach ($rows as $v) {
      $result[$v->subject_id][$v->regulation_id] = $v->name;
    }
    return $result;
  }

  /**
   * Ambil regulations (tabel regulations) yang sudah published, untuk form view.
   */
  public function getPublishedRegulationsAll()
  {
    return $this->db->get_where('regulations', ['status' => 'PUB'])->result();
  }

  /**
   * Ambil subjects milik sebuah perusahaan dan branch yang aktif.
   */
  public function getSubjects($referenceId)
  {
    return $this->db->get_where('view_compliance_subjects', [
      'reference_id' => $referenceId,
      'status'       => '1',
    ])->result();
  }

  // -----------------------------------------------------------------------
  // WRITE / DELETE METHODS
  // -----------------------------------------------------------------------

  /**
   * Simpan data reference beserta standards dan regulations.
   * Mengembalikan ['id' => $id] jika berhasil, ['axist' => 1] jika sudah ada.
   */
  public function saveData($Data = null)
  {
    $DataStd = isset($Data['standards'])   ? $Data['standards']   : '';
    $DataReg = isset($Data['regulations']) ? $Data['regulations'] : '';

    unset($Data['standards']);
    unset($Data['regulations']);

    if (isset($Data['id'])) {
      $Id                  = $Data['id'];
      $Data['modified_by'] = $this->auth->user_id();
      $Data['modified_at'] = date('Y-m-d H:i:s');
      $this->db->update('references', $Data, ['id' => $Data['id']]);
    } else {
      /* Cek apakah data sudah ada */
      $checkData = $this->db->get_where('references', [
        'company_id' => $Data['company_id'],
        'branch_id'  => $Data['branch_id'],
      ])->num_rows();

      if ($checkData > 0) {
        return ['axist' => 1];
      }

      $Data['created_by'] = $this->auth->user_id();
      $Data['created_at'] = date('Y-m-d H:i:s');
      $this->db->insert('references', $Data);
      $Id = $this->db->insert_id('references');
    }

    /* Simpan Standards */
    if ($DataStd) {
      foreach ($DataStd as $std) {
        if (isset($std['id'])) {
          $std['modified_by'] = $this->auth->user_id();
          $std['modified_at'] = date('Y-m-d H:i:s');
          $this->db->update('ref_standards', $std, ['id' => $std['id']]);
        } else {
          $std['reference_id'] = $Id;
          $std['created_by']   = $this->auth->user_id();
          $std['created_at']   = date('Y-m-d H:i:s');
          $this->db->insert('ref_standards', $std);
        }
      }
    }

    /* Simpan Regulations */
    if ($DataReg) {
      foreach ($DataReg as $reg) {
        if (isset($reg['id']) && $reg['id']) {
          $reg['modified_by'] = $this->auth->user_id();
          $reg['modified_at'] = date('Y-m-d H:i:s');
          $this->db->update('ref_regulations', $reg, ['id' => $reg['id']]);
        } else {
          $reg['reference_id'] = $Id;
          $reg['created_by']   = $this->auth->user_id();
          $reg['created_at']   = date('Y-m-d H:i:s');
          $this->db->insert('ref_regulations', $reg);
        }
      }
    }

    return ['id' => $Id];
  }

  /**
   * Hapus sebuah reference beserta seluruh data relasi (cascade delete).
   * Menjalankan transaksi dan mengembalikan array response standar.
   *
   * @param  int   $id
   * @return array ['status' => 1|0, 'msg' => '...']
   */
  public function deleteReference($id)
  {
    $this->db->trans_begin();
    $this->db->delete('references',              ['id'           => $id]);
    $this->db->delete('ref_standards',           ['reference_id' => $id]);
    $this->db->delete('cross_reference_details', ['reference_id' => $id]);
    $this->db->delete('ref_regulations',         ['reference_id' => $id]);
    $this->db->delete('compliance_details',      ['reference_id' => $id]);
    $this->db->delete('compliance_opports',      ['reference_id' => $id]);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return ['status' => 0, 'msg' => 'Failed to delete data.. Please try again.'];
    }

    $this->db->trans_commit();
    return ['status' => 1, 'msg' => 'Successfully deleted data..'];
  }

  /**
   * Hapus sebuah regulation berdasarkan id.
   * Menjalankan transaksi dan mengembalikan array response standar.
   *
   * @param  int   $id
   * @return array ['status' => 1|0, 'msg' => '...']
   */
  public function deleteRegulation($id)
  {
    $this->db->trans_begin();
    $this->db->delete('ref_regulations', ['id' => $id]);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return ['status' => 0, 'msg' => 'Failed to delete data.. Please try again.'];
    }

    $this->db->trans_commit();
    return ['status' => 1, 'msg' => 'Successfully deleted data..'];
  }
}
