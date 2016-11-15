<?php

/* 
 * Class Name       : Users.php
 * Version          : 1.0
 * List Function    : -
 * Created          : 15/11/2016
 * Modified         : -
 * Changelog Fiture : -
 * Author           : Ibnu
 */

class Users extends CI_Controller {
    
    var $template = 'template';
    
    function __construct() {
        parent::__construct();
        $this->load->model('User');
    }
    
    //Function for login user
    function login() {
        $this->form_validation->set_rules('email','email','required|valid_email');
        $this->form_validation->set_rules('password','password','required');
        $this->form_validation->set_error_delimiters('', '<br/>');
        
        if ($this->form_validation->run() == TRUE) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            
            $user = $this->User->checkLogin($email, $password);
            
            if (!empty($user)) {
                $sessionData['id'] = $user['id'];
                $sessionData['email'] = $user['email'];
                $sessionData['full_name'] = $user['full_name'];
                $sessionData['level'] = $user['level'];
                $sessionData['is_login'] = TRUE;
                
                $this->session->set_userdata($sessionData);
                $this->User->updateLastLogin($user['id']);
                
                if ($this->session->set_userdata('level') == 1) {
                    redirect('admin/dashboard');
                } else {
                    redirect('users/home');
                }
            }
            
            $this->session->set_flashdata('error','Login Failed !, email and password is wrong');
            redirect('users/home');
        }
        
        $data['content'] = 'users/login';
        $this->load->view($this->template, $data);
    }
    
    //Function for Logout
    function logout() {
        $this->session->sess_destroy();
        redirect('users/login'); 
    }
}


