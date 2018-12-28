<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form', 'custom_cookie']);
        $this->load->model('Common_model');
        $this->load->library('session');
        $this->lang->load('common', "english");
        $this->load->library('form_validation');
//        $sessionData = validate_admin_cookie('budfie_cookie', 'admin');
//        if ($sessionData) {
//            $this->session->set_userdata('admininfo', $sessionData);
//        }
//        $this->admininfo = $this->session->userdata('admininfo');
//        if ($this->admininfo) {
//            redirect(base_url() . "admin/users");
//        }
    }

    /*
     * @function:index
     * @param:username:email
     * @param:password:password
     * @description:if email and password are correct then he can login
     */

    public function index() {
        $data = [];
        if ($this->input->post()) {
            $postDataArr = $this->input->post();
            $email = isset($postDataArr['email'])?$postDataArr['email']:"";
            $password = isset($postDataArr['password'])?$postDataArr['password']:"";
            $url = '';
            $postRequest = array(
                'email' => $email,
                'password' => $password
            );
            $response = post_curl($url, $postRequest);
            $response = json_decode($response, TRUE);
        }
        load_outer_views('login/login-page', $data);
    }

}
