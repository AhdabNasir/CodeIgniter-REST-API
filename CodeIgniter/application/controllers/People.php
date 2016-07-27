<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class People extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('peoplemodel');
	}

	public function index()
	{
		$this->load->model('peoplemodel');
		$this->data['names'] = $this->peoplemodel->getPeoples();
		$this->load->view('name_display', $this->data);
	}
	
	public function person() {
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			$name = $this->input->post('name');
			$address = $this->input->post('address');
			$telephone = $this->input->post('telephone');
			
			$data = $this->peoplemodel->insertperson($name, $address, $telephone);
			echo json_encode($data);
		}
		
		elseif ($this->input->server('REQUEST_METHOD') == 'GET') {
		     
			 $personID = $this->input->get('personID');
			 
			 $deleted = $this->peoplemodel->deleteperson($personID);
			 echo json_encode($deleted);
		
		}
	}
	
	
	
	public function user() {
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			$personID = $this->input->post('personID');
			$name = $this->input->post('name');
			$address = $this->input->post('address');
			$telephone = $this->input->post('telephone');
			
			$update = $this->peoplemodel->updatePerson($personID, $name, $address, $telephone);
			echo json_encode($update);
			
	
		}
		
		elseif ($this->input->server('REQUEST_METHOD') == 'GET') {
		     
			 $personID = $this->input->get('personID');
			 
			 $edit = $this->peoplemodel->getPerson($personID);
			 echo json_encode($edit);
		}
	}
	
	
	
}
