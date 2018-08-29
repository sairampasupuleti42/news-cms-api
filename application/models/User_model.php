<?php
class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    /*Check User Before Add*/
    function isUserExists($email)
    {
        $this->db->select("u.user_email");
        $this->db->where("u.user_email", $email);
        $query = $this->db->get("tbl_users u");
        if ($query->num_rows() < 1) {
            return true;
        }
        return false;
    }
    /*Check Token -Forgot password*/
    function getUserByTocken($tocken)
    {
        $this->db->select("u.*");
        $this->db->where("u.user_reset_token", $tocken);
        $query = $this->db->get("tbl_users u");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }
    /*User CRUD Funcs*/
    function addUser($pdata)
    {
        $this->db->insert("tbl_users", $pdata);
        return $this->db->insert_id();
    }
    function updateUser($data, $user_id)
    {
        $this->db->where("user_id", $user_id);
        return $this->db->update("tbl_users", $data);
    }
    function updatePasswordByToken($data, $token)
    {
        $this->db->where("user_reset_token", $token);
        return $this->db->update("tbl_users", $data);
    }
    function getUserList($s = array(), $mode = 'DATA')
    {
        if ($mode == "CNT") {
            $this->db->select("COUNT(1) as CNT");
        } else {
            $this->db->select("u.*");
        }
        if (isset($s['limit']) && isset($s['offset'])) {
            $this->db->limit($s['limit'], $s['offset']);
        }
        $this->db->where_not_in('u.user_id', ['1']);
        $this->db->order_by("u.user_id DESC");
        $query = $this->db->get("tbl_users u");
        if ($query->num_rows() > 0) {
            if ($mode == "CNT") {
                $row = $query->row_array();
                return $row['CNT'];
            }
            return $query->result_array();
        }
        return false;
    }
    function getUserById($user_id)
    {
        $this->db->select("u.*");
        $this->db->where("u.user_id", $user_id);
        $query = $this->db->get("tbl_users u");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }
    function getUserByEmail($email)
    {
        $this->db->select("u.*");
        $this->db->where("u.user_email", $email);
        $query = $this->db->get("tbl_users u");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }
    function delUser($user_id)
    {
        $this->db->where("user_id", $user_id);
        return $this->db->delete("tbl_users");
    }
} ?>