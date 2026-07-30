<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "menus"
 */

class Menus_model extends BF_Model
{

    /**
     * @var string User Table Name
     */
    protected $table_name = 'menus';
    protected $key        = 'id';

    /**
     * @var string Field name to use for created time
     */
    protected $created_field = 'created_on';

    /**
     * @var string Field name to use for modified time
     */
    protected $modified_field = 'modified_on';

    /**
     * @var bool Set created time automatically
     */
    protected $set_created = true;

    /**
     * @var bool Set modified time automatically
     */
    protected $set_modified = true;

    protected $soft_deletes = false;
    protected $date_format = 'datetime';
    protected $log_user = true;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Fetch dataset ordered by Parent ID ASC, then Child ID ASC hierarchically
     */
    public function get_menus_data()
    {
        $query = $this->db->select("
            t1.*,
            t2.title as parent_name
        ")
        ->from("{$this->table_name} as t1")
        ->join("{$this->table_name} as t2", "t1.parent_id = t2.id", "left")
        ->order_by("t1.id", "ASC")
        ->get()
        ->result();

        if (empty($query)) {
            return array();
        }

        // Group by parent_id (preserving id ASC order in each group)
        $children = array();
        foreach ($query as $item) {
            $children[(int)$item->parent_id][] = $item;
        }

        // Build flattened hierarchical tree starting from parent_id = 0
        $result = array();
        $this->build_tree_flat($children, 0, 0, $result);

        // Fallback for any orphaned child menus (parent_id != 0 but parent not found in result)
        foreach ($query as $item) {
            $found = false;
            foreach ($result as $r) {
                if ($r->id == $item->id) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $item->level = 1;
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * Helper to recursively flatten tree nodes: Parents ordered by ID ASC, Children ordered by ID ASC
     */
    private function build_tree_flat(&$children, $parent_id, $level, &$result)
    {
        if (isset($children[$parent_id])) {
            foreach ($children[$parent_id] as $child) {
                $child->level = $level;
                $result[] = $child;
                // Recursively attach sub-children of this item
                $this->build_tree_flat($children, $child->id, $level + 1, $result);
            }
        }
    }

    /**
     * Get parent options for form dropdown
     * @param int|null $current_id
     * @return array
     */
    public function pilih_parent($current_id = null)
    {
        $aMenu = array();
        $aMenu[0] = 'ROOT (Top Level Menu)';

        $this->db->select('id, title, parent_id');
        $this->db->from($this->table_name);
        if (!empty($current_id)) {
            $this->db->where('id !=', $current_id);
        }
        $this->db->order_by('parent_id', 'ASC');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();

        $results = $query->result_array();
        if ($results) {
            foreach ($results as $vals) {
                $prefix = ($vals['parent_id'] == 0) ? '└─ ' : '&nbsp;&nbsp;&nbsp;&nbsp;└─ ';
                $aMenu[$vals['id']] = $prefix . $vals['title'] . ' (ID: ' . $vals['id'] . ')';
            }
        }
        return $aMenu;
    }

    /**
     * Get group menu options array (Back End / Front End)
     * @return array
     */
    public function pilih_menu_group()
    {
        return array(
            '1' => 'Back End',
            '2' => 'Front End'
        );
    }

    /**
     * Legacy helper method for top level menus
     */
    public function pilih_menu()
    {
        $aMenu = array();
        $aMenu[0] = 'ROOT (Top Level Menu)';
        $results = $this->db->where('parent_id', 0)->order_by('id', 'ASC')->get('menus')->result_array();
        if ($results) {
            foreach ($results as $vals) {
                $aMenu[$vals['id']] = $vals['title'];
            }
        }
        return $aMenu;
    }
}
