<ol class="breadcrumb">
    <li><a href="<?php echo base_url()?>">Trang chủ</a></li>
    <?php
    if(isset($this->link)){
    $k = 1;
    for($i = 0; $i < sizeof($this->link); $i++){
        $link = $this->link[$i];
        $links = explode(':',$link);
        if(count($links) == 2){
            $text = $links[0];
            $href= $links[1];
        }else{
            $text = $link;
            $href="";
        }
        $active = ($k == count($this->link))?'class="active end"':'';
    ?>
    <li><a <?php echo $active?> href="<?php echo ($href != '')?site_url($href):'javascript:;'?>"><?=$text?></a></li>
    <?php 
    }
    }?>
</ol>