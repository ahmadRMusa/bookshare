<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function userRegister()
	{
		$input = array(
			'cellphone' => $this->input->get_post('cellphone') ,
			'password' => $this->input->get_post('password') ,
			'confirm' => $this->input->get_post('confirm') ,
			);

		if(empty($input['cellphone'])){
			echoFail('Cellphone field is empty');
			return FALSE;
		}

		if(empty($input['password'])){
			echoFail('Password field is empty');
			return FALSE;
		}

		if(empty($input['confirm'])){
			echoFail('Confirm field is empty');
			return FALSE;
		}

		if($input['password'] != $input['confirm']){
			echoFail('Password and confirm does not match');
			return FALSE;
		}
		$this->load->model('user_model');
		if($this->user_model->isCellphoneDuplicated($input['cellphone']) == TRUE ){
			echoFail('Cellphone has been registered');
			return FALSE;
		}

		list($result , $msg) = $this->user_model->create($input);

		if($result == FALSE){
			echoFail($msg);
			return FALSE;
		}

		$this->user_model->processLogin($input['cellphone']);

		echoSucc('register succ');
		return TRUE;

	}

	public function userLogin()
	{
		$input = array(
			'cellphone' => $this->input->get_post('cellphone') ,
			'password' => $this->input->get_post('password') ,
			);

		if(empty($input['cellphone'])){
			echoFail('Cellphone field is empty');
			return FALSE;
		}

		if(empty($input['password'])){
			echoFail('Password field is empty');
			return FALSE;
		}

		$this->load->model('user_model');
		if($this->user_model->isCellphonePasswordMatched($input) == FALSE){
			echoFail('Cellphone or password is wrong');
			return FALSE;
		}

		$this->user_model->processLogin($input['cellphone']);

		echoSucc('login succ');
		return TRUE;
	}

	public function userLogout(){
		$this->session->sess_destroy();
		echoSucc();
	}

	public function shareBook()
	{
		$input = array(
			'book_id' => $this->input->get_post('book_id') ,
			'description' => $this->input->get_post('description') ,
			);

		if(empty($input['book_id'])){
			echoFail('Book information is lost');
			return FALSE;
		}

		if(empty($input['description'])){
			echoFail('Description field is empty');
			return FALSE;
		}

		if(isLogin() == FALSE){
			echoFail('Have not logined yet ');
			return FALSE;
		}
		$user_id = $this->session->userdata('user_id');

		$this->load->model('share_model');
		if($this->share_model->isDuplicateItem($input['book_id'] , $user_id) == TRUE){
			echoFail('You can only upload one copy of the same book');
			return FALSE;
		}
		
		$item_id = $this->share_model->createItem( $input['book_id'] , $user_id , $input['description'] );

		$output = array(
			'result' => 1 ,
			'item_id' => $item_id
			);
		echo json_encode($output);
		return TRUE;
	}

	public function editProfile()
	{
		$input = array(
			'username' => $this->input->get_post('username') ,
			'cellphone' => $this->input->get_post('cellphone') ,
			'email' => $this->input->get_post('email') ,
			);

		if(empty($input['username'])){
			echoFail('username is empty');
			return FALSE;
		}

		if(empty($input['cellphone'])){
			echoFail('cellphone is empty');
			return FALSE;
		}

		if(empty($input['email'])){
			echoFail('email is empty');
			return FALSE;
		}

		$username = $input['username'];
		$cellphone = $input['cellphone'];
		$email = $input['email'];

		$this->load->model('user_model');
		$user_id = $this->session->userdata('user_id');

		$query = $this->db->query("SELECT * FROM user WHERE cellphone = '$cellphone' AND id != $user_id ");
		if($query->num_rows() != 0){
			echoFail('cellphone is duplicated');
			return FALSE;
		}

		$query = $this->db->query("SELECT * FROM user WHERE username = '$username' AND id != $user_id ");
		if($query->num_rows() != 0){
			echoFail('username is duplicated');
			return FALSE;
		}

		$query = $this->db->query("SELECT * FROM user WHERE email = '$email' AND id != $user_id ");
		if($query->num_rows() != 0){
			echoFail('email is duplicated');
			return FALSE;
		}
		
		if($this->user_model->updateProfile($input , $user_id) == FALSE){
			echoFail('Fail to change profile');
			return FALSE;
		}

		echoSucc('login succ');
		return TRUE;
	}
}
