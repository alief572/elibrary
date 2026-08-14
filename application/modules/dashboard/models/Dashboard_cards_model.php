<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_cards_model extends BF_Model
{
    protected $table_name = 'dashboard_cards';
    protected $key = 'id';
    protected $created_field = 'created_at';
    protected $modified_field = 'modified_at';
    protected $set_created = false;
    protected $set_modified = false;
    protected $soft_deletes = false;

    public function __construct()
    {
        parent::__construct();
        $this->_ensure_table_exists();
    }

    protected function _ensure_table_exists()
    {
        if (!$this->db->table_exists($this->table_name)) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `{$this->table_name}` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL,
                    `link` varchar(255) NOT NULL,
                    `picture` varchar(255) NOT NULL,
                    `sort_order` int(11) DEFAULT 0,
                    `is_active` enum('Y','N') DEFAULT 'Y',
                    `created_at` datetime DEFAULT NULL,
                    `created_by` int(11) DEFAULT NULL,
                    `modified_at` datetime DEFAULT NULL,
                    `modified_by` int(11) DEFAULT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ");
        }
    }

    public function get_active_cards()
    {
        if (!$this->db->table_exists($this->table_name)) {
            return [];
        }
        return $this->db
            ->where('is_active', 'Y')
            ->order_by('sort_order', 'ASC')
            ->order_by('id', 'ASC')
            ->get($this->table_name)
            ->result();
    }

    public function get_all_cards()
    {
        if (!$this->db->table_exists($this->table_name)) {
            return [];
        }
        return $this->db
            ->order_by('sort_order', 'ASC')
            ->order_by('id', 'ASC')
            ->get($this->table_name)
            ->result();
    }

    /**
     * Daftar menu aktif yang punya link valid (bukan #).
     */
    public function get_menu_link_options()
    {
        if (!$this->db->table_exists('menus')) {
            return ['options' => [], 'links' => []];
        }

        $menus = $this->db
            ->select('id, title, link, parent_id')
            ->where('status', 1)
            ->where('link !=', '#')
            ->where('link !=', '')
            ->order_by('parent_id', 'ASC')
            ->order_by('order', 'ASC')
            ->order_by('title', 'ASC')
            ->get('menus')
            ->result();

        $parentTitles = [];
        foreach ($menus as $menu) {
            if ((int) $menu->parent_id === 0) {
                $parentTitles[$menu->id] = $menu->title;
            }
        }

        $options = [];
        $links = [];
        foreach ($menus as $menu) {
            $label = $menu->title;
            if ((int) $menu->parent_id > 0 && isset($parentTitles[$menu->parent_id])) {
                $label = $parentTitles[$menu->parent_id] . ' › ' . $menu->title;
            }
            $options[] = (object) [
                'link' => $menu->link,
                'label' => $label,
            ];
            $links[] = $menu->link;
        }

        return ['options' => $options, 'links' => $links];
    }
}
