<?php
class Category_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function addCategory($pdata)
    {
        $this->db->set("category_created_on", "NOW()", false);
        $this->db->set("category_status", "1", false);
        $this->db->insert("tbl_categories", $pdata);
        return $this->db->insert_id();
    }
    function updateCategory($pdata, $category_id)
    {
        $this->db->where("category_id", $category_id);
        return $this->db->update("tbl_categories", $pdata);
    }
    function delCategory($category_id)
    {
        $this->db->where("category_id", $category_id);
        return $this->db->delete("tbl_categories");
    }
    function getCategoryById($user_id)
    {
        $this->db->select("c.*");
        $this->db->where("c.category_id", $user_id);
        $query = $this->db->get("tbl_categories c");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }
}