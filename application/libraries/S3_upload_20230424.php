<?php

/**
 * Amazon S3 Upload PHP class
 *
 * @version 0.1
 */
class S3_upload {

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('s3');

		$this->CI->config->load('s3', TRUE);
		$s3_config = $this->CI->config->item('s3');
		$this->bucket_name = $s3_config['bucket_name'];
		$this->folder_name = $s3_config['folder_name'];
		$this->s3_url = $s3_config['s3_url'];
	}

	function upload_file($file_path)
	{
		// generate unique filename
		$file = pathinfo($file_path);
		$s3_file = $file['filename'].'-'.rand(1000,1).'.'.$file['extension'];
		$mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file_path);	

		$saved = $this->CI->s3->putObjectFile(
			$file_path,
			$this->bucket_name,
			$this->folder_name.$s3_file,
			S3::ACL_PRIVATE,
			array(),
			$mime_type
		);
		if ($saved) {
			// $command = $this->CI->s3->getObject($this->bucket_name, $this->folder_name.$s3_file);
			// file_put_contents(UPLOADPATH.DIRECTORY_SEPARATOR.$s3_file, $command->body);exit;
			return $this->s3_url.$this->bucket_name.'/'.$this->folder_name.$s3_file;
			// $command = $this->CI->s3->getObject($this->bucket_name, $this->folder_name.$s3_file);
			// file_get_contents($command->body);exit;
			// print_r($command);exit;
		}
	}

}