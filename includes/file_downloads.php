<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// process download request
function scottcart_process_download_request() {
	
	if (isset($_REQUEST['scottcart_f']) && isset($_REQUEST['scottcart_t'])) {
		
		$path = sanitize_text_field($_REQUEST['scottcart_f']);
		$token = sanitize_text_field($_REQUEST['scottcart_t']);
		
		// validate token
		$result = scottcart_validate_download_token($path,$token);
		
		if (isset($result[0]) && $result[0] == "true") {
			
			// get the download url from the order_id
			$file = wp_get_attachment_url($result[1]);
			
			header('Content-Type: application/octet-stream');
			header("Content-Transfer-Encoding: Binary"); 
			header("Content-disposition: attachment; filename=\"" . basename($file) . "\""); 
			readfile($file);
			
			
			
		} elseif (isset($result[0]) && $result[0] == expired) {
			echo __('The download link has expired.','scottcart');
		} else {
			echo __('The download link is not valid.','scottcart');
		}
		
		exit;
	}
}

add_action( 'init', 'scottcart_process_download_request' );


// generate download url
function scottcart_generate_download_url($order_id,$file_id) {
		
		$args = array();
		
		$secret = md5(hash('sha256',wp_salt()));
		$time = time();
		
		$args = array(
			'scottcart_f' => rawurlencode(sprintf('%d:%d:%d',$time,$order_id, $file_id)),
			'scottcart_t' => md5(rawurlencode(sprintf('%d:%d:%d:%d',$time,$order_id, $file_id,$secret))),
		);
		
		$download_url = add_query_arg($args,site_url('index.php'));
		
		return $download_url;
}


// validate token
function scottcart_validate_download_token($path,$token) {
		
		$result = array();
		
		$path = explode(':',rawurldecode($path));
		
		$time = $path[0];
		$order_id = $path[1];
		$file_id = $path[2];
		$secret = md5(hash('sha256',wp_salt()));
		
		
		// see if tokens match
		$test_token = md5(rawurlencode(sprintf('%d:%d:%d:%d',$time,$order_id,$file_id,$secret)));
		if ($token == $test_token) {
			$result[0] = "true";
			$result[1] = $file_id;
		}
		
		// log download ip for order here
		
		
		// do download expire here
		//if ($token == $test_token) { $result = "expired"; }
		
		
		return $result;
}


// force download
function scottcart_force_download($filename = '', $file = '') {
        if ($filename == '' OR $file == '')
        {
            return FALSE;
        }
		
        // Try to determine if the filename includes a file extension.
        // We need it in order to set the MIME type
        if (FALSE === strpos($filename, '.'))
        {
            return FALSE;
        }
		
        // Grab the file extension
        $x = explode('.', $filename);
        $extension = end($x);
		
        // Load the mime types
        @include(APPPATH.'config/mimes'.EXT);
		
        // Set a default mime if we can't find it
        if ( ! isset($mimes[$extension]))
        {
            $mime = 'application/octet-stream';
        }
        else
        {
            $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
        }
		
        // Generate the server headers
        if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE)
        {
            header('Content-Type: "'.$mime.'"');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: ".filesize($file));
        }
        else
        {
            header('Content-Type: "'.$mime.'"');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: ".filesize($file));
        }
		
        scottcart_readfile_chunked($file);
        die;
}


// break up big files
function scottcart_readfile_chunked($file, $retbytes=true) {
       $chunksize = 1 * (1024 * 1024);
       $buffer = '';
       $cnt =0;
		
       $handle = fopen($file, 'r');
       if ($handle === FALSE)
       {
           return FALSE;
       }
		
       while (!feof($handle))
       {
           $buffer = fread($handle, $chunksize);
           echo $buffer;
           ob_flush();
           flush();
			
           if ($retbytes)
           {
               $cnt += strlen($buffer);
           }
       }
		
       $status = fclose($handle);
		
       if ($retbytes AND $status)
       {
           return $cnt;
       }
		
       return $status;
}