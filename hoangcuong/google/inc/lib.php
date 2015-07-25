<?php
class lib{
    function __construct(){
        $this->db = new db();
    }
    
    function get_num_link_no_active($str = ''){
        $sql = "
            SELECT ids FROM wp_terms_link WHERE active = 0
        ";
        if($str != ""){
            $sql .=" AND term_id IN ($str)";
        }
        return $this->db->num_rows($sql);
    }
    function get_num_link_active($str = ''){
        $sql = "
            SELECT ids FROM wp_terms_link WHERE active = 1
        ";
        if($str != ""){
            $sql .=" AND term_id IN ($str)";
        }
        return $this->db->num_rows($sql);
    }
    
    function update_active($ids){
        $vup['active'] = 1;
        $this->db->update('wp_terms_link',$vup,array('ids'=>$ids));
    }
    function ac_active(){
        return $this->db->query("UPDATE wp_terms_link SET active = 0");
    }
    
    function get_row($str = ''){
        $sql = "
            SELECT * FROM wp_terms_link WHERE active = 0
        ";
        if($str != ""){
            $sql .=" AND term_id IN ($str)";
        }
        $sql .="  ORDER BY ids ASC LIMIT 1";
        return $this->db->row($sql);
    }
    
    function check_alias($alias){
        $sql = "SELECT ID FROM wp_posts WHERE post_name = '$alias' LIMIT 1";
        if($this->db->row($sql)){
            return false;
        }else{
            return true;
        }
    }
    
    function check_com($com){
        $sql = "SELECT ID FROM wp_posts WHERE id_com = '$com'";
        return $this->db->num_rows($sql);
    }
    
    function getAllCategory($parent_id = 0){
        $sql = "
                SELECT * FROM wp_terms, wp_term_taxonomy
                WHERE wp_terms.term_id = wp_term_taxonomy.term_id
                AND wp_term_taxonomy.taxonomy = 'category'
                AND wp_term_taxonomy.parent = $parent_id
                ";
        //echo $sql;
        return $this->db->result($sql);
    }
    
    
    function ar_cat_string($catid){
        if($catid == 0){
            return "";
        }else{
            $list = $this->getAllCategory($catid);
            //var_dump($list);
            $str = $catid;
            foreach($list as $rs):
                $str .= $rs->term_id.',';
            endforeach;
            return rtrim($str,",");
        }
    }
}
