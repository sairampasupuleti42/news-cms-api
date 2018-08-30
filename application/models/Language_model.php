<?php

class Language_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function addLanguage($pdata)
    {
        $this->db->insert("tbl_languages", $pdata);
        return $this->db->insert_id();
    }

    function updateLanguage($pdata, $language_id)
    {
        $this->db->where("id", $language_id);
        return $this->db->update("tbl_languages", $pdata);
    }

    function delLanguage($language_id)
    {
        $this->db->where("id", $language_id);
        return $this->db->delete("tbl_languages");
    }

    function getLanguageById($language_id)
    {
        $this->db->select("l.*");
        $this->db->where("l.id", $language_id);
        $query = $this->db->get("tbl_languages l");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    function checkLanguageInDB($language_name)
    {
        $this->db->select('l.*');
        $this->db->where('name', $language_name);
        $query = $this->db->get("tbl_languages l");
        if ($query->num_rows() > 0) {
            return true;
        }
    }

    function searchLanguages()
    {
        $this->db->select('l.*');
        $query = $this->db->get("tbl_languages l");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
}