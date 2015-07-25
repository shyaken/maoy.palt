<?php
/*
Template Name: Download
*/
?>
<? ob_start(); ?>
<?php
	$file_id = get_query_var('file_id');
	$fname =get_query_var("file_name");
	if(!empty($file_id) && !empty($fname)){
		$file = getFileByFileID($file_id);
		foreach($file as $fi){
					$post_id = $fi->post_id;
					$file_name = $fi->file_name;
					$file_url = $fi->file;
					$file_des = $fi->file_des;
					$file_size = formatBytes($fi->file_size);
					$file_hits = $fi->file_hits;
					$file_date = $fi->file_date;
					$file_updated_date = $fi->file_updated_date;
					$file_last_downloaded_date=$fi->file_last_downloaded_date;
					$file_views = $fi->file_views;
		}
		$_SESSION['hits']=$file_hits;		
		if(!isset($_SESSION['file_views'][$file_id])){
			$_SESSION['file_views'][$file_id]=$file_views+1;
			updateFile(array('file_views'=>$file_views+1),array('file_id'=>$file_id));			
		}
	}
		$link = getLinkByFileID($file_id);
		$hit = $_SESSION['hits'] + 1;
		updateFile(array('file_hits'=>$hit,'file_last_downloaded_date'=>date("d-m-Y H:i:s")),array('file_id'=>$file_id));
		header("Location:" . $link);
		exit($link);

		
?>
<?ob_flush();?>