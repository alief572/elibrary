<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * @author Yunas Handra
 * @copyright Copyright (c) 2016, Yunas Handra
 * 
 * This is model class for table "log_5masterbarang"
 */

class Monitoring_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'log_5masterbarang';
    protected $key        = 'kodebarang';

    /**
     * @var string Field name to use for the created time column in the DB table
     * if $set_created is enabled.
     */
    protected $created_field = 'created_on';

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
    protected $soft_deletes = true;

    protected $date_format = 'datetime';

    /**
     * @var bool If true, will log user id in $created_by_field, $modified_by_field,
     * and $deleted_by_field.
     */
    protected $log_user = true;

    /**
     * Function construct used to load some library, do some actions, etc.
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function _update_history($data)
    {
        $data['note']              = $data['note'];
        $data['updated_by']        = $this->auth->user_id();
        $data['updated_at']        = date('Y-m-d H:i:s');

        $this->db->insert('directory_log', $data);
    }

    public function review($data = null)
    {

        if ($data) {
            $this->db->update(
                'procedures',
                [
                    'status'         => $data['status'],
                    'modified_by'     => $this->auth->user_id(),
                    'modified_at'     => date('Y-m-d H:i:s'),
                    'reviewed_by'     => $this->auth->user_id(),
                    'reviewed_at'     => date('Y-m-d H:i:s'),
                ],
                ['id' => $data['id']]
            );
            $thisData = $this->db->get_where('procedures', ['id' => $data['id']])->row();
            $data['directory_id']     = $data['id'];
            $data['new_status']       = $data['status'];
            $data['old_status']       = $thisData->status;
            $data['doc_type']         = 'Procedure';
            unset($data['id']);
            unset($data['status']);
            $this->_update_history($data);
        } else {
            return false;
        }
    }

    public function approval($data = null)
    {
        if ($data) {
            $this->db->update(
                'procedures',
                [
                    'status'         => $data['status'],
                    'modified_by'     => $this->auth->user_id(),
                    'modified_at'     => date('Y-m-d H:i:s'),
                    'approved_by'     => $this->auth->user_id(),
                    'approved_at'     => date('Y-m-d H:i:s'),
                ],
                ['id' => $data['id']]
            );
            $thisData = $this->db->get_where('procedures', ['id' => $data['id']])->row();
            $data['directory_id']     = $data['id'];
            $data['new_status']       = $data['status'];
            $data['old_status']       = $thisData->status;
            $data['doc_type']         = 'Procedure';
            unset($data['id']);
            unset($data['status']);
            $this->_update_history($data);
        } else {
            return false;
        }
    }

    public function revision($data = null)
    {
        if ($data) {
            $this->db->update(
                'procedures',
                [
                    'status'         => $data['status'],
                    'modified_by'     => $this->auth->user_id(),
                    'modified_at'     => date('Y-m-d H:i:s'),
                    'revision_req_by'     => $this->auth->user_id(),
                    'revision_req_at'     => date('Y-m-d H:i:s'),
                ],
                ['id' => $data['id']]
            );

            $thisData = $this->db->get_where('procedures', ['id' => $data['id']])->row();
            $data['directory_id']     = $data['id'];
            $data['new_status']       = $data['status'];
            $data['old_status']       = $thisData->status;
            $data['doc_type']         = 'Procedure';
            unset($data['id']);
            unset($data['status']);
            $this->_update_history($data);
        } else {
            return false;
        }
    }

    public function deletion($data = null)
    {
        if ($data) {
            $this->db->update(
                'procedures',
                [
                    'status'          => $data['status'],
                    'deletion_status' => 'OPN',
                    'modified_by'     => $this->auth->user_id(),
                    'modified_at'     => date('Y-m-d H:i:s'),
                ],
                ['id' => $data['id']]
            );

            $thisData = $this->db->get_where('procedures', ['id' => $data['id']])->row();
            $data['directory_id']     = $data['id'];
            $data['new_status']       = $data['status'];
            $data['old_status']       = $thisData->status;
            $data['doc_type']         = 'Procedure';
            unset($data['id']);
            unset($data['status']);
            $this->_update_history($data);
        } else {
            return false;
        }
    }

    public function rev_deletion($data = null)
    {

        if ($data) {
            $this->db->update(
                'procedures',
                [
                    'deletion_status'     => $data['deletion_status'],
                    'modified_by'     => $this->auth->user_id(),
                    'modified_at'     => date('Y-m-d H:i:s'),
                ],
                ['id' => $data['id']]
            );

            $thisData = $this->db->get_where('procedures', ['id' => $data['id']])->row();
            $data['directory_id']     = $data['id'];
            $data['new_status']       = $data['status'];
            $data['old_status']       = $thisData->status;
            $data['doc_type']         = 'Procedure';
            unset($data['id']);
            unset($data['status']);
            unset($data['deletion_status']);
            $this->_update_history($data);
        } else {
            return false;
        }
    }
}
