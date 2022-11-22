<?php

if (!defined('ABSPATH')) { exit; }

if (!class_exists('WPLMS_Assignments')){
    class WPLMS_Assignments{

	    public static $instance;
	    
	    var $schedule;

	    public static function init(){

	        if ( is_null( self::$instance ) )
	            self::$instance = new WPLMS_Assignments();

	        return self::$instance;
	    }
	    private function getMimeTypes()
        {
            return apply_filters('wplms_assignments_upload_mimes_array',array(
                'JPG' => array(
                                'image/jpeg',
                                'image/jpg',
                                'image/jp_',
                                'application/jpg',
                                'application/x-jpg',
                                'image/pjpeg',
                                'image/pipeg',
                                'image/vnd.swiftview-jpeg',
                                'image/x-xbitmap'),
                'GIF' => array(
                                'image/gif',
                                'image/x-xbitmap',
                                'image/gi_'),
                'PNG' => array(
                                'image/png',
                                'application/png',
                                'application/x-png'),
                'DOCX'=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'RAR'=> 'application/x-rar',
                'ZIP' => array(
                                'application/zip',
                                'application/x-zip',
                                'application/x-zip-compressed',
                                'application/x-compress',
                                'application/x-compressed',
                                'multipart/x-zip'),
                'DOC' => array(
                                'application/msword',
                                'application/doc',
                                'application/text',
                                'application/vnd.msword',
                                'application/vnd.ms-word',
                                'application/winword',
                                'application/word',
                                'application/x-msw6',
                                'application/x-msword'),
                'PDF' => array(
                                'application/pdf',
                                'application/x-pdf',
                                'application/acrobat',
                                'applications/vnd.pdf',
                                'text/pdf',
                                'text/x-pdf'),
                'PPT' => array(
                                'application/vnd.ms-powerpoint',
                                'application/mspowerpoint',
                                'application/ms-powerpoint',
                                'application/mspowerpnt',
                                'application/vnd-mspowerpoint',
                                'application/powerpoint',
                                'application/x-powerpoint',
                                'application/x-m'),
                'PPTX'=> 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'PPS' => 'application/vnd.ms-powerpoint',
                'PPSX'=> 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
                'PSD' => array('application/octet-stream',
                                'image/vnd.adobe.photoshop'
                                ),
                'ODT' => array(
                                'application/vnd.oasis.opendocument.text',
                                'application/x-vnd.oasis.opendocument.text'),
                'XLS' => array(
                                'application/vnd.ms-excel',
                                'application/msexcel',
                                'application/x-msexcel',
                                'application/x-ms-excel',
                                'application/vnd.ms-excel',
                                'application/x-excel',
                                'application/x-dos_ms_excel',
                                'application/xls'),
                'XLSX'=> array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                          'application/vnd.ms-excel'),
                'MP3' => array(
                                'audio/mpeg',
                                'audio/x-mpeg',
                                'audio/mp3',
                                'audio/x-mp3',
                                'audio/mpeg3',
                                'audio/x-mpeg3',
                                'audio/mpg',
                                'audio/x-mpg',
                                'audio/x-mpegaudio'),
                'M4A' => array(
                                'audio/mp4a-latm',
                                'audio/m4a',
                                'audio/mp4'),
                'OGG' => array(
                                'audio/ogg',
                                'application/ogg'),
                'WAV' => array(
                                'audio/wav',
                                'audio/x-wav',
                                'audio/wave',
                                'audio/x-pn-wav'),
                'WMA' => 'audio/x-ms-wma',
                'MP4' => array(
                                'video/mp4v-es',
                                'audio/mp4',
                                'video/mp4'),
                'M4V' => array(
                                'video/mp4',
                                'video/x-m4v'),
                'MOV' => array(
                                'video/quicktime',
                                'video/x-quicktime',
                                'image/mov',
                                'audio/aiff',
                                'audio/x-midi',
                                'audio/x-wav',
                                'video/avi'),
                'WMV' => 'video/x-ms-wmv',
                'AVI' => array(
                                'video/avi',
                                'video/msvideo',
                                'video/x-msvideo',
                                'image/avi',
                                'video/xmpg2',
                                'application/x-troff-msvideo',
                                'audio/aiff',
                                'audio/avi'),
                'MPG' => array(
                                'video/avi',
                                'video/mpeg',
                                'video/mpg',
                                'video/x-mpg',
                                'video/mpeg2',
                                'application/x-pn-mpg',
                                'video/x-mpeg',
                                'video/x-mpeg2a',
                                'audio/mpeg',
                                'audio/x-mpeg',
                                'image/mpg'),
                'OGV' => 'video/ogg',
                '3GP' => array(
                                'audio/3gpp',
                                'video/3gpp'),
                '3G2' => array(
                                'video/3gpp2',
                                'audio/3gpp2'),
                'FLV' => 'video/x-flv',
                'WEBM'=> 'video/webm',
                'APK' => 'application/vnd.android.package-archive',
            ));
        }


        /**
         * Gets allowed file types extensions
         *
         * @return array
         */
        
        public function getAllowedFileExtensions($post_id=null){
            if(empty($post_id) && !isset($_POST['comment_post_ID'])){
                global $post;
                if(isset($post) && is_object($post)){
                  $post_id = $post->ID;  
                }else{
                  return;
                }
            }

            $return = array();
            $pluginFileTypes = $this->getMimeTypes();

            if(isset($_POST['comment_post_ID'])){
                $assignment_id = $_POST['comment_post_ID'];
            }
            
            if(empty($assignment_id)){
                $assignment_id = $post_id;
            }
            $attachment_type=get_post_meta($assignment_id,'vibe_attachment_type',true);
            if(is_array($attachment_type) && in_array('JPG',$attachment_type)){
                $attachment_type[]='JPEG';
            }
            if(empty($attachment_type)){
              $attachment_type=array('JPEG');
            }
            return $attachment_type;
        }


        /**
         * Gets allowed file types for attachment check.
         *
         * @return array
         */

        public function getAllowedMimeTypes($post_id=null)
        {   
            if(empty($post_id)){
                global $post;
                $post_id = $post->ID;
            }
            $return = array();
            $pluginFileTypes = $this->getMimeTypes();
            $ext=$this->getAllowedFileExtensions($post_id);
            foreach($ext as $key){
                if(array_key_exists($key, $pluginFileTypes)){
                    if(!function_exists('finfo_file') || !function_exists('mime_content_type')){
                        if(($key ==  'DOCX') || ($key == 'DOC') || ($key == 'PDF') ||
                            ($key == 'ZIP') || ($key == 'RAR')){
                            $return[] = 'application/octet-stream';
                        }
                    }
                    if(is_array($pluginFileTypes[$key])){
                        foreach($pluginFileTypes[$key] as $fileType){
                            $return[] = $fileType;
                        }
                    } else {
                        $return[] = $pluginFileTypes[$key];
                    }
                }
            }
            return $return;
        }

        function _mime_content_type($filename) {

            /**
            *    mimetype
            *    Returns a file mimetype. Note that it is a true mimetype fetch, using php and OS methods. It will NOT
            *    revert to a guessed mimetype based on the file extension if it can't find the type.
            *    In that case, it will return false
            **/
            if (!file_exists($filename) || !is_readable($filename)) return false;
            if(class_exists('finfo')){
                $result = new finfo();
                if (is_resource($result) === true) {
                    return $result->file($filename, FILEINFO_MIME_TYPE);
                }
            }
            
             // Trying finfo
             if (function_exists('finfo_open')) {
               $finfo = finfo_open(FILEINFO_MIME);
               $mimeType = finfo_file($finfo, $filename);
               finfo_close($finfo);
               // Mimetype can come in text/plain; charset=us-ascii form
               if (strpos($mimeType, ';')) list($mimeType,) = explode(';', $mimeType);
               return $mimeType;
             }
            
             // Trying mime_content_type
             if (function_exists('mime_content_type')) {
               return mime_content_type($filename);
             }
            

             // Trying to get mimetype from images
             $imageData = getimagesize($filename);
             if (!empty($imageData['mime'])) {
               return $imageData['mime'];
             }
             // Trying exec
             if (function_exists('exec')) {
               $mimeType = exec("/usr/bin/file -i -b $filename");
               if(strpos($mimeType,';')){
                 $mimeTypes = explode(';',$mimeType);
                 return $mimeTypes[0];
               }
               if (!empty($mimeType)) return $mimeType;
             }
            return false;
        }

        /**
         * This one actually will need explaining, it's hard
         *
         * @param array $existing
         * @return array
         */

        function getmaxium_upload_file_size($post_id = null){
            if(empty($post_id)){
             global $post;
             if(isset($post) && is_object($post) && isset($post->ID))
                $post_id = $post->ID;
            }
            $upload_size = 1024;
            $max_upload = (int)(ini_get('upload_max_filesize'));
            $max_post = (int)(ini_get('post_max_size'));
            $memory_limit = (int)(ini_get('memory_limit'));
            $upload_mb = min($max_upload, $max_post, $memory_limit);
            $attachment_size=get_post_meta($post_id,'vibe_attachment_size',true); 
            
            if(isset($attachment_size) && is_numeric($attachment_size)){
                if($attachment_size > $upload_mb && empty($this->plupload_assignment_e_d )){
                    $upload_size=$upload_mb;
                }else{
                    $upload_size=$attachment_size;
                }
                
            }

            return $upload_size;
        }


	}
}