<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Standards_model extends BF_Model
{

  /**
   * @var string  User Table Name
   */
  protected $table_name = 'standards';
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
    $data['company_id']   = $this->company;
    $data['scope_id']     = $data['scopes'];
    unset($data['scopes']);

    /* REGULATION */
    if (isset($data['id'])) {
      $data['modified_by']  = $this->auth->user_id();
      $data['modified_at']  = date('Y-m-d H:i:s');
      $this->db->update('standards', $data, ['id' => $data['id']]);
      $regId                = $data['id'];
    } else {
      $data['created_by']   = $this->auth->user_id();
      $data['created_at']   = date('Y-m-d H:i:s');
      $this->db->insert('standards', $data);
      $result               = $this->db->limit(1)->order_by('created_at', 'DESC')->get_where('standards')->row();
      $regId                = $result->id;
    }
    return $regId;
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

    foreach ($data['dtl']['desc'] as $k => $desc) {
      $n++;
      $ArrData[$k]['id']              =  $data['pasal_id'] . '-' . $n;
      $ArrData[$k]['regulation_id']   =  $data['regulation_id'];
      $ArrData[$k]['pasal_id']        =  $data['pasal_id'];
      $ArrData[$k]['description']     =  $desc;
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
      'modified_by'  => $this->auth->user_id(),
      'modified_at'  => date('Y-m-d H:i:s'),
    ];
    $this->db->update('regulation_paragraphs', $data, ['id' => $id]);
  }
}
