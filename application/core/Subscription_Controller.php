<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Subscription_Controller extends CI_Controller {
	public function __construct()
	{
		 parent::__construct();
		//  require realpath(APPPATH . '../vendor/autoload.php');
		
	}

	public static function checkSubscription(){
		// $CI =& get_instance();
        // $CI->db->select('count(id) as total');
        // $CI->db->where('id',1);
        // $CI->db->order_by('id','desc');
        // $CI->db->limit(1);
        // $query = $CI->db->get('subscription');
        // $query->result();
        // echo "<pre>";
        // print_r($query);
        die('here');
	}

}