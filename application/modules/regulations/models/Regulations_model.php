<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Regulations_model extends BF_Model
{

  /**
   * @var string  User Table Name
   */
  protected $table_name = 'regulations';
  protected $key        = 'id';

  /**
   * @var string Field name to use for the created time column in the DB table
   * if $set_created is enabled.
   */
  protected $created_field = 'create_on';

  /**
   * @var string Field name to use for the modified time column in the DB
   * table if $set_modified is enabled.
   */
  protected $modified_field = 'modified_on';

  /**
   * @var bool Set the created time automatically on a new record (if true)
   */
  protected $set_created = true;

  /**
   * @var bool Set the modified time automatically on editing a record (if true)
   */
  protected $set_modified = true;
  /**
   * @var string The type of date/time field used for $created_field and $modified_field.
   * Valid values are 'int', 'datetime', 'date'.
   */
  /**
   * @var bool Enable/Disable soft deletes.
   * If false, the delete() method will perform a delete of that row.
   * If true, the value in $deleted_field will be set to 1.
   */
  protected $soft_deletes = false;

  protected $date_format = 'datetime';

  /**
   * @var bool If true, will log user id in $created_by_field, $modified_by_field,
   * and $deleted_by_field.
   */
  protected $log_user = true;

  /**
   * Function construct used to load some library, do some actions, etc.
   */
  protected $company;
  public function __construct()
  {
    parent::__construct();
    $this->company      = $this->session->company->id_perusahaan;
  }

  private function _getIdPasal()
  {
    $sql    = 'SELECT MAX(RIGHT(id,4)) as maxId FROM regulation_pasal';
    $result = $this->db->query($sql)->row();
    $maxId  = $result->maxId;
    $count  = 1;

    if ($maxId > 0) {
      $count = $maxId + 1;
    }

    return "PAS" . str_pad($count, 4, "0", STR_PAD_LEFT);
  }

  private function _getIdPasalDesc($pasal_id)
  {
    $sql      = "SELECT MAX(SUBSTR(id,9)+0) as maxId FROM regulation_paragraphs WHERE pasal_id = '$pasal_id'";
    $result   = $this->db->query($sql)->row();
    $maxId    = $result->maxId;
    $count    = 1;

    if ($maxId > 0) {
      $count = $maxId + 1;
    }

    return $count;
  }

  public function saveData($data = null)
  {
    $subjects   = (isset($data['subjects']) ? $data['subjects'] : '');
    $scopes     = (isset($data['scopes']) ? $data['scopes'] : '');
    $data['company_id'] = $this->company;
    unset($data['subjects']);
    unset($data['scopes']);

    /* REGULATION */
    if (isset($data['id'])) {
      $data['modified_by'] = $this->auth->user_id();
      $data['modified_at'] = date('Y-m-d H:i:s');
      $this->db->update('regulations', $data, ['id' => $data['id']]);
      $regId = $data['id'];
    } else {
      $data['created_by'] = $this->auth->user_id();
      $data['created_at'] = date('Y-m-d H:i:s');
      $this->db->insert('regulations', $data);
      $result = $this->db->limit(1)->order_by('created_at', 'DESC')->get_where('regulations')->row();
      $regId = $result->id;
    }

    /* REGULATION SUBJECT*/

    $dataSubject = $this->db->get_where('subjects')->result();
    $ArrSubjects = [];
    foreach ($dataSubject as $dtSbj) {
      $ArrSubjects[$dtSbj->id] = $dtSbj;
    }

    if ($subjects) {
      $existData = $this->db->get_where('regulation_subjects', ['regulation_id' => $regId, 'company_id' => $this->company])->num_rows();
      if ($existData > 0) {
        $this->db->delete('regulation_subjects', ['regulation_id' => $regId, 'company_id' => $this->company]);
      }

      foreach ($subjects as $key => $sbj) {
        $dataSubjects[$key]   = [
          'company_id'    => $this->company,
          'regulation_id' => $regId,
          'subject_id'    => $sbj,
          'subject_name'    => $ArrSubjects[$sbj]->name,
        ];
      }

      $this->db->insert_batch('regulation_subjects', $dataSubjects);
    }

    $dataScope = $this->db->get_where('scopes')->result();
    $ArrScope = [];
    foreach ($dataScope  as $dtScp) {
      $ArrScope[$dtScp->id] = $dtScp;
    }

    /* REGULATION SCOPE*/
    if ($scopes) {
      $existData = $this->db->get_where('regulation_scopes', ['regulation_id' => $regId, 'company_id' => $this->company])->num_rows();
      if ($existData > 0) {
        $this->db->delete('regulation_scopes', ['regulation_id' => $regId, 'company_id' => $this->company]);
      }

      foreach ($scopes as $key => $scp) {
        $dataScopes[$key]   = [
          'company_id'      => $this->company,
          'regulation_id'   => $regId,
          'scope_id'        => $scp,
          'scope_name'      => $ArrScope[$scp]->name,
        ];
      }

      $this->db->insert_batch('regulation_scopes', $dataScopes);
    }

    return $regId;
  }

  public function savePasal($data = null)
  {
    if (!$data['id']) {
      $id = $this->_getIdPasal();
      $data['id']         = $id;
      $data['created_by'] = $this->auth->user_id();
      $data['created_at'] = date('Y-m-d H:i:s');
      unset($data['pasal']);
      $this->db->insert('regulation_pasal', $data);
    } else {
      $data['modified_by'] = $this->auth->user_id();
      $data['modified_at'] = date('Y-m-d H:i:s');
      $this->db->update('regulation_pasal', $data, ['id' => $data['id']]);
    }
  }

  public function deletePasal($id)
  {
    $this->db->delete('regulation_paragraphs', ['pasal_id' => $id]);
    $this->db->delete('regulation_pasal', ['id' => $id]);
  }

  /*  */

  public function savePasalDesc($data = null)
  {
    $id = $this->_getIdPasalDesc($data['pasal_id']);
    $n = $id;
    foreach ($data['dtl'] as $k => $desc) {
      $n++;
      $ArrData[$k]['id']              =  $data['pasal_id'] . '-' . $n;
      $ArrData[$k]['regulation_id']   =  $data['regulation_id'];
      $ArrData[$k]['pasal_id']        =  $data['pasal_id'];
      $ArrData[$k]['name']            =  $desc['name'];
      $ArrData[$k]['order']           =  $desc['order'];
      $ArrData[$k]['description']     =  $desc['desc'];
      $ArrData[$k]['created_by']      = $this->auth->user_id();
      $ArrData[$k]['created_at']      = date('Y-m-d H:i:s');
    }
    if ($ArrData) {
      $this->db->insert_batch('regulation_paragraphs', $ArrData);
    }
  }

  public function updatePasalDesc($data = null)
  {
    $id = $data['id'];
    $data = [
      'description' => $data['desc'],
      'name' => $data['name'],
      'order' => $data['order'],
      'modified_by'  => $this->auth->user_id(),
      'modified_at'  => date('Y-m-d H:i:s'),
    ];
    $this->db->update('regulation_paragraphs', $data, ['id' => $id]);
  }
}
