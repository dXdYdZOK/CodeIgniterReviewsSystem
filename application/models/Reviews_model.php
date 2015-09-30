<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reviews_model extends CI_Model
	{
	public function __construct()
		{
		parent::__construct();
		//$this->load->database();
		}
	//protected======================================================================
	//public=========================================================================	
	public function get_count_reviews($user_id)
		{
		$user_id=(integer)$user_id;
		$this->db->where('user_id',$user_id);
		return $this->db->count_all_results('reviews');
		}
	public function get_reviews($user_id,$page,$perPage)
		{
		$user_id=(integer)$user_id;
		$this->db->select('reviews.id,reviews.user_id,reviews.sender_id,reviews.status,reviews.text,users.username,users.first_name,users.last_name');
		$this->db->where('user_id',$user_id);
		$this->db->join('users','users.id = reviews.sender_id');
		if($page==false)
			$page=0;
		$this->db->limit($perPage, $page*$perPage);
		$this->db->order_by('timestamp', 'DESC');
		$query = $this->db->get('reviews');
		return $query->result();
		}
	public function get_review($review_id)
		{
		$ureview_id=(integer)$review_id;
		$this->db->select('reviews.id,reviews.user_id,reviews.sender_id,reviews.status,reviews.text,users.username,users.first_name,users.last_name');
		$this->db->where('reviews.id',$review_id);
		$this->db->join('users','users.id = reviews.sender_id');
		$query=$this->db->get('reviews');
		return $query->row();
		}
	public function add_review($data)
		{
		$this->db->insert('reviews', $data);
		}
	public function edit_review($data)
		{
		$this->db->where('id',$data['id']);
		unset($data['id']);
		$this->db->set($data);
		$this->db->update('reviews');
		}
	public function delete_review($id)
		{
		$this->db->delete('reviews',array('id'=>$id));
		}
	public function is_valid_user_id($user_id)
		{
		$user_id=(integer)$user_id;
		$this->db->where('id',$user_id);
		return $this->db->count_all_results('users')>0;
		}
	}