<?php

/**
 * Load cUrl constants and functions definitions 
 */
require_once 'constants.php';
require_once 'functions.php';

/**
 * Wrapper for file_get_contents, which aims very basic PHP curl functionality
 */
class Purl
{
    /**
     * Curl options container
     * 
     * @var type 
     */
    private $_options = array();
    
    /**
     * Request parameters
     * 
     * @var array
     */
    private $_params = array();
    
    /**
     * Headers list
     * 
     * @var array
     */
    private $_headers = array();
    
    /**
     * Request notifications log
     * 
     * @var array
     */
    private $_logs = array();
    
    /**
     * Last error message
     * 
     * @var type 
     */
    private $_error = 0;
    
    /**
     * Last error number
     * 
     * @var number
     */
    private $_errorno = '';
    
    /**
     * URL to call
     * 
     * @var string 
     */
    private $_url = '';
    
    /**
     * Response data
     * 
     * @var string
     */
    private $_result = '';
    
    /**
     * Request method
     * 
     * @var string
     */
    private $_method = 'GET';
    
    /**
     * Default user agent
     * 
     * @var string
     */
    private $_agent = 'pUrl PHP Client V 0.1';
    
    /**
     * Info container
     * 
     * @var array
     */
    private $_info = array();
    
    private static $_infoMap = array(
        CURLINFO_HTTP_CODE => 'http_code',
        CURLINFO_HEADER_OUT => 'request_header'
    );
    
    /**
     * Setter for curl options
     * 
     * @param int $option CURLOPT constant
     * @param mixed $value option value to set
     * @return void
     */
    public function setOption($option, $value)
    {
        $this->_options[$option] = $value;
        
        switch ($option) {
            case CURLOPT_POSTFIELDS:
                $this->_params = $value;
                $this->_method = 'POST';
                break;
            case CURLOPT_HTTPHEADER:
                $this->_headers = $value;
                break;
            case CURLOPT_URL:
                $this->_url = $value;
                break;
            case CURLOPT_POST:
                $this->_method = $value ? 'POST' : $this->_method;
                break;
            case CURLOPT_PUT:
                $this->_method = $value ? 'POST' : $this->_method;
                break;
            case CURLOPT_CUSTOMREQUEST:
                $this->_method = $value;
                break;
            case CURLOPT_USERAGENT:
                $this->_agent = $value;
                break;
        }
        
        return true;
    }
    
    /**
     * Get last error number
     * 
     * @return int
     */
    public function getErrorNumber()
    {        
        return $this->_errorno;
    }
    
    /**
     * Get last error message
     * 
     * @return type
     */
    public function getError()
    {        
        return $this->_error;
    }
    
    /**
     * 
     * 
     * @param type $opt
     * @return type
     */
    public function getInfo($opt = 0)
    {        
        if ($opt === 0) {
            
            return $this->_info;
            
        } 
        
        if (isset(self::$_infoMap[$opt])) {
            return $this->_info[self::$_infoMap[$opt]];
        }
        
        trigger_error(__FUNCTION__ . PURL_NOT_SUPPORTED_MSG, PURL_ERROR_TYPE);
    }
    
    /**
     * 
     * 
     * @param type $opt
     * @return type
     */
    public function getLog()
    {        
        return $this->_logs;
    }
    
    /**
     * Execute "curl" call
     * 
     * @return type
     */
    public function execute()
    {     
        $headers = $query = '';
        
        if (!empty($this->_params)) {            
            if (is_array($this->_params)) {
                $query = http_build_query($this->_params, null, '&');
            } else {
                $query = $this->_params;
            }
        }
        
        // add cookie header
        if (isset($this->_options[CURLOPT_COOKIE])) {
            $this->_headers['Cookie'] = $this->_options[CURLOPT_COOKIE];            
        }
                
        if ($this->_headers) {
            foreach ($this->_headers as $value) {
                $headers .= $value . "\r\n";
            }
        }

        if (!preg_match('/Content-type/', $headers)) $headers .= "Content-type: " . "application/x-www-form-urlencoded"."\r\n";

        return $this->_call($query, $this->_info['request_header'] = $headers);
    }
    
    /**
     * Make request using file_get_contents.
     * Trying to set as much curl original settings as possible
     * 
     * @param string $query GET query string
     * @param array $headers headers to send along with request
     * @return boolean|string
     */
    protected function _call($query, $headers)
    {        
        $options =
            array('http'=>
              array(
                'method' => $this->_method,
                'header' => $headers,
                'ignore_errors' => true,
                'user_agent' => $this->_agent,
              )
            );
                
        // HTTP context settings
        if (isset($this->_options[CURLOPT_TIMEOUT])) $options['http']['timeout'] = $this->_options[CURLOPT_TIMEOUT];
        
        if (isset($this->_options[CURLOPT_FOLLOWLOCATION])) $options['http']['follow_location'] = $this->_options[CURLOPT_FOLLOWLOCATION];
        
        if (isset($this->_options[CURLOPT_MAXREDIRS])) $options['http']['max_redirects'] = $this->_options[CURLOPT_MAXREDIRS];
        
        if (isset($this->_options[CURLOPT_PROXY])) {
            $options['http']['proxy'] = $this->_options[CURLOPT_PROXY];
            $options['http']['request_fulluri'] = true;
        }
        
        // SSL context settings
        if (isset($this->_options[CURLOPT_SSL_VERIFYPEER])) $options['ssl']['verify_peer'] = $this->_options[CURLOPT_SSL_VERIFYPEER];
        if (isset($this->_options[CURLOPT_CAINFO])) $options['ssl']['cafile'] = $this->_options[CURLOPT_CAINFO];
        
        if ($this->_method === 'POST') {
            $options['http']['content'] = $query;
        } elseif (!empty($query)) {
            $this->_url .= '?' . $query;
        }
        
        $context = stream_context_create($options);
        
        stream_context_set_params($context, array('notification' => function() {
            // not working yet :/
            $this->_logs[] = func_get_args();
        }));
        
        $reporting = error_reporting(0);
        
        if (!empty($this->_options[CURLOPT_NOBODY])) {
            $handler = fopen($this->_url, 'r', false, $context);
        } else {
            $this->_result = file_get_contents($this->_url, false, $context);
        }
        
        $this->_info['http_code'] = explode(' ', $http_response_header[0])[1];
                
        error_reporting($reporting);
                
        // on failure
        if ($this->_result === false || (isset($handler) && $handler === false)) {
            
            $this->_errorHandler();

            return false;
        }

        if (!empty($this->_options[CURLOPT_HEADER])) {
            $this->_result = implode($http_response_header, "\r\n") . "\r\n\r\n" . $this->_result;
        }
                        
        return isset($this->_options[CURLOPT_RETURNTRANSFER]) ? $this->_result : true;
    }
    
    /**
     * Handle request errors
     * Set error message and message number
     * 
     * @return void
     */
    private function _errorHandler()
    {
        $error = error_get_last();
        
        $parts = parse_url($this->_url);
        
        if (strpos($error['message'], 'php_network_getaddresses')) {
            $this->_error = "Couldn't resolve host '" . $parts['host']  . "'"; 
            $this->_errorno = 6; 
        } elseif (strpos($error['message'], 'No such file or directory')) {
            $this->_error = "Protocol " . $parts['scheme']  . " not supported or disabled in libcurl"; 
            $this->_errorno = 1;             
        } elseif (strpos($error['message'], 'failed to open stream')) {
            $this->_error = "couldn't connect to host"; 
            $this->_errorno = 7;             
        } else {
            $this->_error = "Unknown Purl error. PHP error: " . $error['message']; 
            $this->_errorno = 100;                         
        }
    }
}
