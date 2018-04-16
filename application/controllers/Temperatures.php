<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 9. 4. 2018
 * Time: 19:20
 */
class Temperatures extends CI_Controller {

    function __construct() {
    parent::__construct();
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->model('Temperatures_model');
}

    public function index(){
        $data = array();

        //ziskanie sprav zo session
        if($this->session->userdata('success_msg')){
            $data['success_msg'] = $this->session->userdata('success_msg');
            $this->session->unset_userdata('success_msg');
        }
        if($this->session->userdata('error_msg')){
            $data['error_msg'] = $this->session->userdata('error_msg');
            $this->session->unset_userdata('error_msg');
        }

        $data['temperatures'] = $this->Temperatures_model->getRows();
        $data['title'] = 'Temperature List';

        //nahratie zoznamu teplot
        $this->load->view('templates/header', $data);
        $this->load->view('temperatures/index', $data);
        $this->load->view('templates/footer');
    }

    // Zobrazenie detailu o teplote
    public function view($id){
        $data = array();

        //kontrola, ci bolo zaslane id riadka
        if(!empty($id)){
            $data['temperatures'] = $this->Temperatures_model->getRows($id);
            $data['title'] = $data['temperatures']['measurement_date'];

            //nahratie detailu zaznamu
            $this->load->view('templates/header', $data);
            $this->load->view('temperatures/view', $data);
            $this->load->view('templates/footer');
        }else{
            redirect('/temperatures');
        }
    }

    // pridanie zaznamu
    public function add(){
        $data = array();
        $postData = array();

        //zistenie, ci bola zaslana poziadavka na pridanie zazanmu
        if($this->input->post('postSubmit')){
            //definicia pravidiel validacie
            $this->form_validation->set_rules('measurement_date', 'date of measurement', 'required');
            $this->form_validation->set_rules('temperature', 'temperature', 'required');
            $this->form_validation->set_rules('sky', 'sky value', 'required');
            $this->form_validation->set_rules('user', 'user id', 'required');

            //priprava dat pre vlozenie
            $postData = array(
                'measurement_date' => $this->input->post('measurement_date'),
                'temperature' => $this->input->post('temperature'),
                'sky' => $this->input->post('sky'),
                'user' => $this->input->post('user'),
                'description' => $this->input->post('description'),
            );

            //validacia zaslanych dat
            if($this->form_validation->run() == true){
                //vlozenie dat
                $insert = $this->Temperatures_model->insert($postData);

                if($insert){
                    $this->session->set_userdata('success_msg', 'Temperature has been added successfully.');
                    redirect('/temperatures');
                }else{
                    $data['error_msg'] = 'Some problems occurred, please try again.';
                }
            }
        }
        $data['users'] = $this->Temperatures_model->get_users_dropdown();
        $data['users_selected'] = '';
        $data['post'] = $postData;
        $data['title'] = 'Create Temperature';
        $data['action'] = 'Add';

        //zobrazenie formulara pre vlozenie a editaciu dat
        $this->load->view('templates/header', $data);
        $this->load->view('temperatures/add-edit', $data);
        $this->load->view('templates/footer');
    }

    // aktualizacia dat
    public function edit($id){
        $data = array();

        //ziskanie dat z tabulky
        $postData = $this->Temperatures_model->getRows($id);

        //zistenie, ci bola zaslana poziadavka na aktualizaciu
        if($this->input->post('postSubmit')){
            //definicia pravidiel validacie
            $this->form_validation->set_rules('measurement_date', 'date of measurement', 'required');
            $this->form_validation->set_rules('temperature', 'temperature value', 'required');
            $this->form_validation->set_rules('sky', 'sky value', 'required');
            $this->form_validation->set_rules('user', 'user id', 'required');

            // priprava dat pre aktualizaciu
            $postData = array(
                'measurement_date' => $this->input->post('measurement_date'),
                'temperature' => $this->input->post('temperature'),
                'sky' => $this->input->post('sky'),
                'user' => $this->input->post('user'),
                'description' => $this->input->post('description'),
            );

            //validacia zaslanych dat
            if($this->form_validation->run() == true){
                //aktualizacia dat
                $update = $this->Temperatures_model->update($postData, $id);

                if($update){
                    $this->session->set_userdata('success_msg', 'Temperature has been updated successfully.');
                    redirect('/temperatures');
                }else{
                    $data['error_msg'] = 'Some problems occurred, please try again.';
                }
            }
        }

        $data['users'] = $this->Temperatures_model->get_users_dropdown();
        $data['users_selected'] = $postData['user'];
        $data['post'] = $postData;
        $data['title'] = 'Update Temperature';
        $data['action'] = 'Edit';

        //zobrazenie formulara pre vlozenie a editaciu dat
        $this->load->view('templates/header', $data);
        $this->load->view('temperatures/add-edit', $data);
        $this->load->view('templates/footer');
    }

    // odstranenie dat
    public function delete($id){
        //overenie, ci id nie je prazdne
        if($id){
            //odstranenie zaznamu
            $delete = $this->Temperatures_model->delete($id);

            if($delete){
                $this->session->set_userdata('success_msg', 'Temperature has been removed successfully.');
            }else{
                $this->session->set_userdata('error_msg', 'Some problems occurred, please try again.');
            }
        }

        redirect('/temperatures');
    }
}