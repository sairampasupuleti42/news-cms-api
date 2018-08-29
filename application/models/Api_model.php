<?php
class Api_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('user_agent');
    }
    function addAccessLog()
    {
        $pdata['ip_address'] = $this->input->ip_address();
        $pdata['accessed_on'] = date('Y-m-d H:i:s A');
        $pdata['platform'] = $this->agent->platform();
        $this->db->insert("tbl_access_logs", $pdata);
        return $this->db->insert_id();
    }
    function getAuth()
    {
        $this->db->select("*");
        $query = $this->db->get("tbl_api");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }
}