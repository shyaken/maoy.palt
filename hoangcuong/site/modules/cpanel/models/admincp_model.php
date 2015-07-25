<?php
class admincp_model extends model{
    function __construct(){
        parent::__construct();
    }
    
    function getAllCategory($parent_id = 0){
        $sql = "
                SELECT * FROM wp_terms, wp_term_taxonomy
                WHERE wp_terms.term_id = wp_term_taxonomy.term_id
                AND wp_term_taxonomy.taxonomy = 'category'
                AND wp_term_taxonomy.parent = $parent_id
                ";
        return $this->db->result($sql);
    }
}
