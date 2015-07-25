<?php echo form_open_multipart(uri_string(), array('id'=>'admindata'));
$banner = $this->config->item('banner');
$ext = end(explode('.',$banner));
?>
<table class="form">
    <tr>
        <td class="label" style="width: 150px;">Upload File</td>
        <td>
            <input type="file" name="userfile"> Kích thước: 960 x 120
            <?if($banner != ''){?>
                <?if($ext != 'swf'){?>
                    <img src="<?=base_url_site()?>data/banner.png" alt="">
                <?}else{?>
                    <embed width="960" align="middle" height="120" quality="high" wmode="transparent" allowscriptaccess="always"  type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" alt="" src="<?=base_url_site()?>data/<?=$banner?>">
                <?}?>
            <?}?>
        </td>
    </tr>
</table>
<?php echo form_close();?>