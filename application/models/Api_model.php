<?php

class Api_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function addGallery($pdata)
    {
        $this->db->set("created_on", "NOW()", false);
        $this->db->set("created_by", $_SESSION['USER_ID'], false);
        $this->db->insert("tbl_gallery", $pdata);
        return $this->db->insert_id();
    }

    function addAbout($pdata)
    {
        $this->db->set("created_on", "NOW()", false);
        $this->db->insert("tbl_about", $pdata);
        return $this->db->insert_id();
    }



    function updateGallery($pdata, $gallery_id)
    {
        $this->db->where("gallery_id", $gallery_id);
        return $this->db->update("tbl_gallery", $pdata);
    }

    function updateAbout($pdata, $pk_id)
    {
        $this->db->where("pk_id", $pk_id);
        return $this->db->update("tbl_about", $pdata);
    }



    function delGallery($gallery_id)
    {
        $this->db->where("gallery_id", $gallery_id);
        return $this->db->delete("tbl_gallery");
    }

    function getGalleryById($gallery_id)
    {
        $this->db->select("m.*");
        $this->db->where("gallery_id", $gallery_id);
        $query = $this->db->get("tbl_gallery m");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    function getGalleryBySlug($str)
    {
        $this->db->select("m.*");
        $this->db->where("gallery_slug", $str);
        $query = $this->db->get("tbl_gallery m");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    function getCategories()
    {
        $this->db->select("c.category_name,c.category_id");
        $query = $this->db->get("category_tbl c");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }


    function getCategoryName($id){
        $this->db->select("c.category_name");
        $this->db->where("c.category_id", $id);
        $query = $this->db->get("category_tbl c");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }



    function searchGallery($s = array(), $mode = "DATA")
    {
        if ($mode == "CNT") {
            $this->db->select("COUNT(1) as CNT");
        } else {
            $this->db->select("m.*");
        }
        if (isset($s['limit']) && isset($s['offset'])) {
            $this->db->limit($s['limit'], $s['offset']);
        }
        if (isset($s['is_active']) && !empty($s['is_active'])) {
            $this->db->where("m.is_active", $s['is_active']);
        }
        if (isset($s['category']) && !empty($s['category'])) {
            $this->db->where("m.category", $s['category']);
        }
        if (isset($s['sub_category_id']) && !empty($s['sub_category_id'])) {
            $this->db->where("m.sub_category_id", $s['sub_category_id']);
        }
        if (isset($s['gallery_id']) && !empty($s['gallery_id'])) {
            $this->db->where("m.gallery_id", $s['gallery_id']);
        }
        if (isset($s['gallery_id']) && !empty($s['gallery_id'])) {
            $this->db->where("m.gallery_id", $s['gallery_id']);
        }
        if (isset($s['posted_by']) && !empty($s['posted_by'])) {
            $this->db->where("m.posted_by ", $s['posted_by']);
        }
        $this->db->order_by("m.created_on DESC");
        $this->db->group_by("m.gallery_id");
        $query = $this->db->get("tbl_gallery m");
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            if ($mode == "CNT") {
                $row = $query->row_array();
                return $row['CNT'];
            }
            return $query->result_array();
        }
        return false;
    }
}