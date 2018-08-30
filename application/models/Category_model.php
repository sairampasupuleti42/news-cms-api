<?php

class Category_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function addCategory($pdata)
    {
        $this->db->insert("tbl_categories", $pdata);
        return $this->db->insert_id();
    }

    function updateCategory($pdata, $category_id)
    {
        $this->db->where("id", $category_id);
        return $this->db->update("tbl_categories", $pdata);
    }

    function delCategory($category_id)
    {
        $this->db->where("id", $category_id);
        return $this->db->delete("tbl_categories");
    }

    function getCategoryById($category_id)
    {
        $this->db->select("c.*");
        $this->db->where("c.id", $category_id);
        $query = $this->db->get("tbl_categories c");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    function checkCategoryInDB($category_name)
    {
        $this->db->select('*');
        $this->db->where('name', $category_name);
        $query = $this->db->get("tbl_categories c");
        if ($query->num_rows() > 0) {
            return true;
        }
    }

    function searchCategories($s = array(), $mode = "DATA")
    {
        if ($mode == "CNT") {
            $this->db->select("COUNT(1) as CNT");
        } else {
            $this->db->select("c.*");
        }
        if (isset($s['limit']) && isset($s['offset'])) {
            $this->db->limit($s['limit'], $s['offset']);
        }
        if(isset($s['language_id']) && !empty($s['language_id'])){
            $this->db->where('language_id',$s['language_id']);
        }
        $this->db->order_by("c.id DESC");
        $query = $this->db->get("tbl_categories c");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
}