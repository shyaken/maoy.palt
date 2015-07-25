<?php
  class CI_pagi{
      function __construct(){
          $this->CI = get_instance();
      }
      function pagination($num,$offset,$limit,$pageid){
          //$id_blog = $this->input->post('id_blog');
            $page = $offset;
            $adjacents = 2;

            if($page) 
                $start = ($page - 1) * $limit;             //first item to display on this page
            else
                $start = 0;                                //if no page var is given, set start to 0
            
 
            $total_pages = $num;
            /* Setup page vars for display. */
            if ($page == 0) $page = 1;                    //if no page var is given, default to 1.
            $prev = $page - 1;                            //previous page is page - 1
            $next = $page + 1;                            //next page is page + 1
            $lastpage = ceil($total_pages/$limit);        //lastpage is = total pages / items per page, rounded up.
            $lpm1 = $lastpage - 1;                        //last page minus 1
            
            /* 
                Now we apply our rules and draw the pagination object. 
                We're actually saving the code to a variable in case we want to draw it more than once.
            */
            $pagination = "";
            if($lastpage > 1)
            {    
                $pagination .= "<div class=\"pages\">";
                $pagination .='<div class="pagebar-mainbody">';
                //previous button
                if ($page > 1) 
                    $pagination.= "<a href=\"javascript:;\" onclick=\"GoToPage($page-1,$pageid)\">&laquo; Previous</a>";
                else
                    $pagination.= "<a href=\"javascript:;\">&lt;</a>";    
                
                //pages    
                if ($lastpage < 6 + ($adjacents * 2))    //not enough pages to bother breaking it up
                {    
                    for ($counter = 1; $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"pagebar-selections\"><span class=\"pagelink-current\">$counter</span></span>";
                        else
                            $pagination.= "<a href=\"javascript:; \" onclick=\"GoToPage($counter,$pageid)\">$counter</a>";                    
                    }
                }
                elseif($lastpage > 4 + ($adjacents * 2))    //enough pages to hide some
                {
                    //close to beginning; only hide later pages
                    if($page < 1 + ($adjacents * 2))        
                    {
                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                        {
                            if ($counter == $page)
                                $pagination.= "<span class=\"pagebar-selections\"><span class=\"pagelink-current\">$counter</span></span>";
                            else
                                $pagination.= "<a href=\"javascript:; \" onlick=\"GoToPage($counter,$pageid)\">$counter</a>";                    
                        }
                        $pagination.= "...";
                        $pagination.= "<a href=\"javascript:; \" onlick=\"GoToPage($lpm1,$pageid)\">$lpm1</a>";
                        $pagination.= "<a href=\"javascript:; \" onlick=\"GoToPage($lastpage,$pageid)\">$lastpage</a>";        
                    }
                    //in middle; hide some front and some back
                    elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                    {
                        $pagination.= "<a href=\"javascript:; \" onclick=\"GoToPage(1)\">1</a>";
                        $pagination.= "<a href=\"javascript:;\" onclick=\"GoToPage(2)\">2</a>";
                        $pagination.= "...";
                        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                        {
                            if ($counter == $page)
                                $pagination.= "<span class=\"pagebar-selections\"><span class=\"current\">$counter</span></span>";
                            else
                                $pagination.= "<a href=\"javascript:;\" onclick=\"GoToPage($counter,$pageid)\">$counter</a>";                    
                        }
                        $pagination.= "...";
                        $pagination.= "<a href=\"javascript:;\" onclick=\"GoToPage($lpm1,$pageid)\">$lpm1</a>";
                        $pagination.= "<a href=\"javascript:;\" onclick=\"GoToPage($lastpage,$pageid)\">$lastpage</a>";        
                    }
                    //close to end; only hide early pages
                    else
                    {
                        $pagination.= "<a href=\"javascript:;\" onclick=\"GoToPage(1,$pageid)\">1</a>";
                        $pagination.= "<a href=\"javascript:;\" onclick=\"GoToPage(2,$pageid)\">2</a>";
                        $pagination.= "...";
                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                        {
                            if ($counter == $page)
                                $pagination.= "<span class=\"pagebar-selections\"><span class=\"current\">$counter</span></span>";
                            else
                                $pagination.= "<a href=\"javascript:;\" onclick=\"GoToPage($counter,$pageid)\">$counter</a>";                    
                        }
                    }
                }
                
                //next button
                if ($page < $counter - 1) 
                    $pagination.= "<a href=\"javascript:;\" onclick=\"GoToPage($page+1,$pageid)\">&raquo; Next</a>";
                else
                    $pagination.= "<a href=\"javascript:;\">&raquo; Next</a>";
               return $pagination.= "</div></div>\n";
            }                
      }
      
      function page($num,$offset,$limit,$gopage = 'go'){
          //$id_blog = $this->input->post('id_blog');
            $page = $offset;
            $adjacents = 2;

            if($page) 
                $start = (($page - 1) * $limit);             //first item to display on this page
            else
                $start = 0;                                //if no page var is given, set start to 0
            
 
            $total_pages = $num;
            /* Setup page vars for display. */
            if ($page == 0) $page = 1;                    //if no page var is given, default to 1.
            $prev = $page - 1;                            //previous page is page - 1
            $next = $page + 1;                            //next page is page + 1
            $lastpage = ceil($total_pages/$limit);        //lastpage is = total pages / items per page, rounded up.
            $lpm1 = $lastpage - 1;                        //last page minus 1
            
            /* 
                Now we apply our rules and draw the pagination object. 
                We're actually saving the code to a variable in case we want to draw it more than once.
            */
            $pagination = "";
            if($lastpage > 1)
            {    

                $pagination .='<span class="pagebar-mainbody">';
                //previous button
                if ($page > 1){ 
                    $pagination.= "<a href=\"javascript:;\" class=\"prev\" onclick=\"$gopage($page-1)\">&laquo; Previous</a>";
                }else{
                    $pagination.= "<a href=\"javascript:;\" class=\"prev\">&laquo; Previous</a>";    
                }
                
                //pages    
                if ($lastpage < 6 + ($adjacents * 2))    //not enough pages to bother breaking it up
                {    
                    for ($counter = 1; $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"pagebar-selections\"><span class=\"current\">$counter</span></span>";
                        else
                            $pagination.= "<a href=\"javascript:; \" onclick=\"$gopage($counter)\">$counter</a>";                    
                    }
                }
                elseif($lastpage > 4 + ($adjacents * 2))    //enough pages to hide some
                {
                    //close to beginning; only hide later pages
                    if($page < 1 + ($adjacents * 2))        
                    {
                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                        {
                            if ($counter == $page)
                                $pagination.= "<span class=\"pagebar-selections\"><span class=\"current\">$counter</span></span>";
                            else
                                $pagination.= "<a href=\"javascript:; \" onclick=\"$gopage($counter)\">$counter</a>";                    
                        }
                        $pagination.= "...";
                        $pagination.= "<a href=\"javascript:; \" onclick=\"$gopage($lpm1)\">$lpm1</a>";
                        $pagination.= "<a href=\"javascript:; \" onclick=\"$gopage($lastpage)\">$lastpage</a>";        
                    }
                    //in middle; hide some front and some back
                    elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                    {
                        $pagination.= "<a href=\"javascript:; \" onclick=\"$gopage(1)\">1</a>";
                        $pagination.= "<a href=\"javascript:;\" onclick=\"$gopage(2)\">2</a>";
                        $pagination.= "...";
                        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                        {
                            if ($counter == $page)
                                $pagination.= "<span class=\"pagebar-selections\"><span class=\"current\">$counter</span></span>";
                            else
                                $pagination.= "<a href=\"javascript:; \" onclick=\"$gopage($counter)\">$counter</a>";                    
                        }
                        $pagination.= "...";
                        $pagination.= "<a href=\"javascript:;\" onclick=\"$gopage($lpm1)\">$lpm1</a>";
                        $pagination.= "<a href=\"javascript:;\" onclick=\"$gopage($lastpage)\">$lastpage</a>";        
                    }
                    //close to end; only hide early pages
                    else
                    {
                        $pagination.= "<a href=\"javascript:;\" onclick=\"$gopage(1)\">1</a>";
                        $pagination.= "<a href=\"javascript:;\" onclick=\"$gopage(2)\">2</a>";
                        $pagination.= "...";
                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                        {
                            if ($counter == $page)
                                $pagination.= "<span class=\"pagebar-selections\"><span class=\"current\">$counter</span></span>";
                            else
                                $pagination.= "<a href=\"javascript:;\" onclick=\"$gopage($counter)\">$counter</a>";                    
                        }
                    }
                }
                
                //next button
                if ($page < $counter - 1){ 
                    //$page = $page +1;
                    $pagination.= "<a href=\"javascript:;\"class=\"next\" onclick=\"$gopage($page+1)\">&raquo; Next</a>";
                }else{
                    $pagination.= "<a href=\"javascript:;\" class=\"next\">&raquo; Next</a>";
                }
               return $pagination.= "</span>\n";
            }                
      }            
  }
?>
