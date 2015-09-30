<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reviews extends CI_Controller {

	function __construct()
		{
			parent::__construct();
			//$this->load->database();
			$this->load->library(array('ion_auth','form_validation','reviews'));
			$this->load->helper(array('url'));
			$this->load->model('reviews_model');
			$this->load->model('ion_auth_model');
		}

	public function index($user_id,$page=false)
	{
		//var_dump($category,$page);
		$user_id=(integer)$user_id;
		
		$perPage=1;
		$this->load->library('pagination');
		$config['base_url'] = "/reviews/index/{$user_id}/";
		$config['total_rows'] = $this->reviews_model->get_count_reviews($user_id);
		$config['per_page'] = $perPage;
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config); 
		$pagination_html=$this->pagination->create_links();
		
		$reviews=$this->reviews_model->get_reviews($user_id,$page,$perPage);
		$user=$this->ion_auth_model->user($user_id)->row();
		$username=$user->username.'('.$user->first_name.' '.$user->last_name.')';
		//$this->load->view('header');
		$this->load->view('reviews/list',array('reviews'=>$reviews,'pagination_html'=>$pagination_html,'username'=>$username,'user_id'=>$user_id));
		//$this->load->view('footer');
	}
	public function add($user_id)
		{
		if(!$this->ion_auth->logged_in())
			{
            redirect('auth/login/', 'refresh');
			exit;
			}
		$user_id=(integer)$user_id;
		$sender_id=$this->ion_auth->get_user_id();
		if($user_id==$sender_id)
			{
            redirect('reviews/index/'.$user_id.'/', 'refresh');
			exit;
			}
		$this->form_validation->set_rules('status', 'Review status', 'required|greater_than[-2]|less_than[2]');
		$this->form_validation->set_rules('text', 'Review text', 'trim|required|max_length[65535]|min_length[10]');
		$this->form_validation->set_rules('user_id','User ID',array(
			'required','greater_than[0]','is_natural',
			array('username_callable', 
				array($this->reviews_model, 'is_valid_user_id')
				)
			));
		$this->form_validation->set_rules('sender_id','Sender ID',array(
			'required','greater_than[0]','is_natural',
			array('username_callable', 
				array($this->reviews_model, 'is_valid_user_id')
				)
			));
		if ($this->form_validation->run() == FALSE)
			{
			$to_user=$this->ion_auth_model->user($user_id)->row();
			$this->load->view('reviews/add',array('user_id'=>$user_id,'sender_id'=>$sender_id,'to_user'=>$to_user,'action'=>'add/'.$user_id));
			}
		else
			{
			$_POST['timestamp']=time();
			$this->reviews_model->add_review($_POST);
			$this->load->view('reviews/success',array('user_id'=>$user_id));
			}
		}
	public function edit($review_id)
		{
		$review_id=(integer)$review_id;
		if(!$this->ion_auth->logged_in())
			{
            redirect('auth/login/', 'refresh');
			exit;
			}
		$review=$this->reviews_model->get_review($review_id);
		$sender_id=$review->sender_id;
		$user_id=$review->user_id;
		$current_user_id=$this->ion_auth->get_user_id();
		if(!($this->ion_auth->is_admin()||($sender_id==$current_user_id)))
			{
			redirect('/reviews/index/'.$user_id.'/','refresh');
			exit;
			}
		$to_user=$this->ion_auth_model->user($user_id)->row();
		if(empty($_POST))
			{
			foreach($review as $key=>$value)
				$_POST[$key]=$value;
			$this->load->view('reviews/add',array('user_id'=>$user_id,'sender_id'=>$sender_id,'to_user'=>$to_user,'action'=>'edit/'.$review_id));
			}
		else
			{
			//Устанавливаем правила валидации
			$this->form_validation->set_rules('status', 'Review status', 'required|greater_than[-2]|less_than[2]');
			$this->form_validation->set_rules('text', 'Review text', 'trim|required|max_length[65535]|min_length[10]');
			$this->form_validation->set_rules('user_id','User ID',array(
				'required','greater_than[0]','is_natural',
				array('username_callable', 
					array($this->reviews_model, 'is_valid_user_id')
					)
				));
			$this->form_validation->set_rules('sender_id','Sender ID',array(
				'required','greater_than[0]','is_natural',
				array('username_callable', 
					array($this->reviews_model, 'is_valid_user_id')
					)
				));
			if ($this->form_validation->run() == FALSE)
				{
				$this->load->view('reviews/add',array('user_id'=>$user_id,'sender_id'=>$sender_id,'to_user'=>$to_user,'action'=>'edit/'.$review_id));
				}
			else
				{
				$_POST['timestamp']=time();
				$_POST['id']=$review_id;
				$this->reviews_model->edit_review($_POST);
				$this->load->view('reviews/success_edit',array('user_id'=>$user_id));
				}
			}
		
		}
	public function delete($review_id)
		{
		$review_id=(integer)$review_id;
		if(!$this->ion_auth->logged_in())
			{
            redirect('auth/login/', 'refresh');
			exit;
			}
		$review=$this->reviews_model->get_review($review_id);
		$sender_id=$review->sender_id;
		$user_id=$review->user_id;
		$current_user_id=$this->ion_auth->get_user_id();
		if($this->ion_auth->is_admin()||($sender_id==$current_user_id))
			$this->reviews_model->delete_review($review_id);
		redirect('/reviews/index/'.$user_id.'/','refresh');
		}
}
