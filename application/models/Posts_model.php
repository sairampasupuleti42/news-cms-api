<?php

class Posts_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function addPost($pdata)
    {
        $this->db->set("post_created_on", "NOW()", false);
        $this->db->insert("tbl_posts", $pdata);
        return $this->db->insert_id();
    }

    function updatePost($pdata, $post_id)
    {
        $this->db->where("post_id", $post_id);
        return $this->db->update("tbl_posts", $pdata);
    }

    function delPost($post_id)
    {
        $this->db->where("post_id", $post_id);
        return $this->db->delete("tbl_posts");
    }

    function searchPosts($s = array(), $mode = "DATA")
    {
        if ($mode == "CNT") {
            $this->db->select("COUNT(1) as CNT");
        } else {
            $this->db->select("p.*");
        }

        if (isset($s['limit']) && isset($s['offset'])) {
            $this->db->limit($s['limit'], $s['offset']);
        }
        if (isset($s['post_id'])) {
            $this->db->where("p.post_id !=", $s['post_id']);
        }
        if (isset($s['language_id'])) {
            $this->db->where("p.language_id", $s['language_id']);
        }
        if (isset($s['category_id'])) {
            $this->db->where("p.category_id", $s['category_id']);
        }
        $this->db->group_by("p.post_id");
        $this->db->order_by("p.post_id DESC");
        $query = $this->db->get("tbl_posts p");

        if ($query->num_rows() > 0) {
            if ($mode == "CNT") {
                $row = $query->row_array();
                return $row['CNT'];
            }
            return $query->result_array();
        }
        return false;
    }

    function getPostById($post_id)
    {
        $this->db->select("p.*");
        $this->db->where("p.post_id", $post_id);
        $query = $this->db->get("tbl_posts p");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    function getPostBySlug($slug)
    {
        $this->db->select("m.*");
        $this->db->like('m.post_permalink',$slug);
        $query = $this->db->get("tbl_posts m");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }
}