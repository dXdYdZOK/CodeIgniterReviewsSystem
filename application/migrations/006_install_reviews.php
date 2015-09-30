<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_install_reviews extends CI_Migration {

	public function up()
		{
		$this->dbforge->drop_table('reviews', TRUE);
	
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'MEDIUMINT',
				//'constraint' => '8',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'user_id'=>array(
				'type'=>'MEDIUMINT',
				'unsigned'=>true
				),
			'sender_id'=>array(
				'type'=>'MEDIUMINT',
				'unsigned'=>true
				),
			'status'=>array(
				'type'=>'SMALLINT',
				'unsigned'=>false,
				),
			'text' => array(
				'type' => 'TEXT',
				'constraint' => '65535',
			),
			'timestamp'=>array(
				'type'=>'BIGINT',
				'unsigned'=>true
				),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('reviews');
		
		$data = array(
			array(
				'id'=>1,
				'user_id'=>1,
				'sender_id'=>1,
				'status'=>1,
				'text'=>'Это положительный отзыв',
				'timestamp'=>time(),
			),
			array(
				'id'=>2,
				'user_id'=>1,
				'sender_id'=>1,
				'status'=>-1,
				'text'=>'Это отрицательный отзыв',
				'timestamp'=>time(),
			),
		);
		$this->db->insert_batch('reviews', $data);
		}
		
	public function down()
		{
		$this->dbforge->drop_table('reviews', TRUE);
		}
	}