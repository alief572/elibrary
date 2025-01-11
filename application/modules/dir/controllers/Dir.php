<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Syamsudin
 * @copyright Copyright (c) 2021, Syamsudin
 *
 * This is controller for Perusahaan
 */

class Dir extends Admin_Controller
{
    // protected $data;
    var $data;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('image_lib');
        $this->template->set([
            'title' => 'Action Plan',
            'icon' => 'fa fa-list'
        ]);

        date_default_timezone_set("Asia/Bangkok");
    }


    public function index()
    {
        $config['image_library'] = 'imagemagick';
        $config['source_image'] = '/logo/importa.jpg';
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = 75;
        $config['height']       = 50;

        $this->image_lib->initialize($config);
        // $this->load->library('image_lib', $config);

        // $this->image_lib->resize();
        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
        echo '<pre>';
        print_r($this->image_lib);
        echo '<pre>';
        exit;



        $Dir = "directory/";
        $dir = scandir($Dir);

        $this->template->set($dir);
        $this->template->render('index');
    }
}
