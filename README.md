pUrl | PHP cUrl lib replacement
===========================================

Some hosting providers such as Google App Engine (for security reasons) do not support PHP cUrl lib. 
So here is very simple replacement for the subset of cUrl base functionality written using PHP built in file stream handlers (file_get_contents, fopen). 
The library defines all the CURL constants and functions, but it does not support by far all the native cUrl features (e.g. curl_multi). 
However it is enough to use with Facebook SDK, Hybrid Auth and banch of other third party libraries, just try out if it works for you and let me know, if not:) 

Supported Functions
--------

 * curl_init
 * curl_copy_handle
 * curl_setopt (not supported options will be just ignored)
 * curl_setopt_array (same thing)
 * curl_errno (very little subset of native codes)
 * curl_error (u know...)
 * curl_close
 * curl_exec

** plans **

 curl_getinfo() - at least some parameters could be returned
 cookie file handling should be implemented as well
 what else?

Using
---------------

 * Copy the library code to your project directory
 * Include the library main class, if curl is not supported
 * Make use of curl methods

```php
    <?php
        if (!function_exists('curl_init')) {
            require_once 'path/to/Purl.php';
        }
        
        $ch = curl_init('http://www.example.com');

        // etc ...
    ?>
```

Not well tested yet, so please do not use for production;) 

Have fun:)