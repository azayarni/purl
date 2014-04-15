<?php

define('PURL_NOT_SUPPORTED_MSG', ' is not (yet) supported by pUrl');
define('PURL_ERROR_TYPE', E_USER_WARNING);

/**
 * Initialize a Purl session
 * @param string $url [optional]
 * If provided, the CURLOPT_URL option will be set
 * to its value. You can manually set this using the
 * curl_setopt function.
 * 
 * @return Purl object on success, FALSE on errors.
 */
function curl_init($url = null) {    
    $ch = new \Purl();
    
    if (!is_null($url)) $ch->setOption(CURLOPT_URL, $url);
    
    return $ch;
}

/**
 * Copy a Purl object along with all of its preferences
 * @param Purl $ch
 * @return Purl a new Ourl object.
 */
function curl_copy_handle(\Purl $ch) {
    return clone $ch; 
}

/**
 * Set an option for a Purl transfer
 * @param Purl $ch
 * @param int $option The CURLOPT_XXX option to set.
 * @param mixed $value
 * @return bool TRUE on success or FALSE on failure.
 */
function curl_setopt(\Purl $ch, $option, $value) {
    return $ch->setOption($option, $value);
}

/**
 * Set multiple options for a Purl transfer
 * @param Purl $ch
 * @param array $options
 * An array specifying which options to set and their values.
 * The keys should be valid curl_setopt constants or
 * their integer equivalents.
 * 
 * @return bool TRUE if all options were successfully set. If an option could
 * not be successfully set, FALSE is immediately returned, ignoring any
 * future options in the options array.
 */
function curl_setopt_array(\Purl $ch , array $options) {
    
    foreach ($options as $option => $value) {
        
        if (!$ch->setOption($option, $value)) {
            return false;
        }
    }
    
    return true;
}

/**
 * Return the last error number
 * @link http://php.net/manual/en/function.curl-errno.php
 * @param Purl $ch
 * @return int the error number or 0 (zero) if no error
 * occurred.
 */
function curl_errno(\Purl $ch) {
    return $ch->getErrorNumber();
}

/**
 * (PHP 4 &gt;= 4.0.3, PHP 5)<br/>
 * Return a string containing the last error for the current session
 * @link http://php.net/manual/en/function.curl-error.php
 * @param Purl $ch
 * @return string the error message or '' (the empty string) if no
 * error occurred.
 */
function curl_error(\Purl $ch) {
    return $ch->getError();
}

/**
 * (PHP 4 &gt;= 4.0.2, PHP 5)<br/>
 * Close a Purl session
 * @link http://php.net/manual/en/function.curl-close.php
 * @param Purl $ch
 * @return void No value is returned.
 */
function curl_close(\Purl &$ch) {
    unset($ch);
}

/**
 * (PHP 4 &gt;= 4.0.2, PHP 5)<br/>
 * Perform a Purl session
 * @link http://php.net/manual/en/function.curl-exec.php
 * @param Purl $ch
 * @return mixed <b>TRUE</b> on success or <b>FALSE</b> on failure. However, if the <b>CURLOPT_RETURNTRANSFER</b>
 * option is set, it will return
 * the result on success, <b>FALSE</b> on failure.
 */
function curl_exec(\Purl $ch) {
    return $ch->execute();
}

/**
 * NOT YET FULLY SUPPORTED!!
 * (PHP 4 &gt;= 4.0.4, PHP 5)<br/>
 * Get information regarding a specific transfer
 * @link http://php.net/manual/en/function.curl-getinfo.php
 * @param resource $ch
 * @param int $opt [optional] <p>
 * This may be one of the following constants:
 * <b>CURLINFO_EFFECTIVE_URL</b> - Last effective URL
 * @return mixed If <i>opt</i> is given, returns its value as a string.
 * Otherwise, returns an associative array with the following elements
 * (which correspond to <i>opt</i>), or <b>FALSE</b> on failure:
 * "url"
 * "content_type"
 * "http_code"
 * "header_size"
 * "request_size"
 * "filetime"
 * "ssl_verify_result"
 * "redirect_count"
 * "total_time"
 * "namelookup_time"
 * "connect_time"
 * "pretransfer_time"
 * "size_upload"
 * "size_download"
 * "speed_download"
 * "speed_upload"
 * "download_content_length"
 * "upload_content_length"
 * "starttransfer_time"
 * "redirect_time"
 * "certinfo"
 * "request_header" (This is only set if the <b>CURLINFO_HEADER_OUT</b>
 * is set by a previous call to <b>curl_setopt</b>)
 */
function curl_getinfo(\Purl $ch, $opt = 0) {
    return $ch->getInfo($opt);
}

//////////////// NOT SUPPORTED FUNCTIONS //////////////////////////////////////

function curl_version ($age = 'CURLVERSION_NOW') {
    trigger_error(__FUNCTION__ . PURL_NOT_SUPPORTED_MSG, PURL_ERROR_TYPE);
}

function curl_multi_init () {
    trigger_error(__FUNCTION__ . PURL_NOT_SUPPORTED_MSG, PURL_ERROR_TYPE);
}

function curl_multi_add_object ($mh, $ch) {
    trigger_error(__FUNCTION__ . PURL_NOT_SUPPORTED_MSG, PURL_ERROR_TYPE);
}

function curl_multi_remove_object ($mh, $ch) {
    trigger_error(__FUNCTION__ . PURL_NOT_SUPPORTED_MSG, PURL_ERROR_TYPE);
}

function curl_multi_select ($mh, $timeout = 1.0) {
    trigger_error(__FUNCTION__ . PURL_NOT_SUPPORTED_MSG, PURL_ERROR_TYPE);
}

function curl_multi_exec ($mh, &$still_running) {
    trigger_error(__FUNCTION__ . PURL_NOT_SUPPORTED_MSG, PURL_ERROR_TYPE);
}

function curl_multi_getcontent ($ch) {
    trigger_error(__FUNCTION__ . PURL_NOT_SUPPORTED_MSG, PURL_ERROR_TYPE);
}

function curl_multi_info_read ($mh, &$msgs_in_queue = null) {
    trigger_error(__FUNCTION__ . PURL_NOT_SUPPORTED_MSG, PURL_ERROR_TYPE);
}

function curl_multi_close ($mh) {
    trigger_error(__FUNCTION__ . PURL_NOT_SUPPORTED_MSG, PURL_ERROR_TYPE);
}