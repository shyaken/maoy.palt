<?php
class term_model extends model{
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
    
    function getAllLink($limit, $offset, $term_id = 0){
        $sql = "SELECT * FROM wp_terms, wp_term_taxonomy, wp_terms_link
                WHERE wp_terms.term_id = wp_term_taxonomy.term_id
                AND wp_term_taxonomy.taxonomy = 'category'
                AND wp_terms.term_id = wp_terms_link.term_id";
        if($term_id != 0){
            $ar_id = $this->getArr($term_id);
            $sql .=' AND wp_terms.term_id IN ('.implode(',',$ar_id).')';
        }
        $sql .=" ORDER BY wp_terms_link.ids DESC LIMIT $limit OFFSET $offset";
        return $this->db->result($sql);
    }
    
    function getNumLink($term_id = 0){
        $sql = "SELECT wp_terms.term_id FROM wp_terms, wp_term_taxonomy, wp_terms_link
                WHERE wp_terms.term_id = wp_term_taxonomy.term_id
                AND wp_term_taxonomy.taxonomy = 'category'
                AND wp_terms.term_id = wp_terms_link.term_id
                ";
        if($term_id != 0){
            $ar_id = $this->getArr($term_id);
            $sql .=' AND wp_terms.term_id IN ('.implode(',',$ar_id).')';
        }
        return $this->db->num_rows($sql);
    }
    
    function getArr($term_id){
        $ar_id[] = $term_id;
        $list = $this->getAllCategory($term_id);
        foreach($list as $rs):
            $ar_id[] = $rs->term_id;
        endforeach;
        return $ar_id;
    }
}
