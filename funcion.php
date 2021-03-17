<?php

function upload_attachment_to_s3( $post_id, $data = null, $file_path = null, $force_new_s3_client = false, $remove_local_files = true ) {
	$return_metadata = $this->get_attachment_metadata( $post_id );
	if ( is_wp_error( $return_metadata ) ) {
		return $return_metadata;
	}

	// Allow S3 upload to be hijacked / cancelled for any reason
	$pre = apply_filters( 'as3cf_pre_upload_attachment', false, $post_id, $data );
	if ( $this->upload_should_be_cancelled( $pre ) ) {
		return $pre;
	}

	// Check file exists locally before attempting upload
	if ( ! $this->local_file_exists() ) {
		$error_msg = sprintf( __( 'File %s does not exist', 'amazon-s3-and-cloudfront' ), $file_path );

		return $this->return_upload_error( $error_msg, $return_metadata );
	}

	// check mime type of file is in allowed S3 mime types
	if ( ! $this->is_valid_mime_type() ) {
		$error_msg = sprintf( __( 'Mime type %s is not allowed', 'amazon-s3-and-cloudfront' ), $type );

		return $this->return_upload_error( $error_msg, $return_metadata );
	}

	$s3object = $this->get_attachment_s3_info( $post_id );
	$acl = $this->get_s3object_acl( $s3object );

	$s3client = $this->get_s3client( $region, $force_new_s3_client );

	$args = array(
		'Bucket'       => $s3object['bucket'],
		'Key'          => $s3object['key'],
		'SourceFile'   => $s3object['source_file'],
		'ACL'          => $acl,
		'ContentType'  => $s3object['mime_type'],
		'CacheControl' => 'max-age=31536000',
		'Expires'      => date( 'D, d M Y H:i:s O', time() + 31536000 ),
	);

	$s3client->putObject( $args );

	$this->maybe_remove_files( $args, $s3object );

	return $s3object;
}
