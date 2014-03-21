<?php

namespace TE\Mvc;

/**
 * Request 
 * 
 * @uses AbstractRequest
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Request
{
    /**
     * agent ip
     * 
     * @var mixed
     * @access private
     */
    private $_ip;

    /**
     * langs
     * 
     * @var array
     * @access private
     */
    private $_acceptLangs = false;

    /**
     * 基础目录 
     * 
     * @var string
     * @access private
     */
    private $_baseUrl = NULL;
    
    /**
     * 路径信息
     *
     * @access private
     * @var string
     */
    private $_pathInfo = NULL;

    /**
     * 请求方法 
     * 
     * @var string
     * @access private
     */
    private $_method = NULL;

    /**
     * 请求完整地址
     * 
     * @var string
     * @access private
     */
    private $_requestUri = NULL;

    /**
     * _requestRoot 
     * 
     * @var string
     * @access private
     */
    private $_requestRoot = NULL;

    /**
     * 来路
     * 
     * @var string
     * @access private
     */
    private $_referer = false;

    /**
     * 是否ssl 
     * 
     * @var mixed
     * @access private
     */
    private $_isSecure = NULL;

    /**
     * 是否移动设备
     * 
     * @var mixed
     * @access private
     */
    private $_isMobile = NULL;

    /**
     * json字符串
     *
     * @var array
     */
    private $_jsonParams = array();

    /**
     * 参数列表
     *
     * @var array
     * @access private
     */
    private $_params = array();

    /**
     * 初始化变量
     */
    public function __construct()
    {
        if (preg_match("/^application\/json/i", $_SERVER['HTTP_ACCEPT'])) {
            $this->_jsonParams = json_decode(file_get_contents('php://input'), true, 16);
        }
    }

    /**
     * setParams
     *
     * @param array $params
     * @static
     * @access public
     * @return void
     */
    public function setParams(array $params)
    {
        $this->_params = array_merge($this->_params, $params);
    }

    /**
     * 获取客户端识别串 
     * 
     * @static
     * @access public
     * @return string
     */
    public function getAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'PHP ' . PHP_VERSION;
    }

    /**
     * 获取前端传递变量
     * 
     * @param string $name
     * @access public
     * @return array
     */
    public function getArg($name)
    {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        } else if (isset($_GET[$name])) {
            return $_GET[$name];
        } else if (isset($this->_jsonParams[$name])) {
            return $this->_jsonParams[$name];
        }

        return false;
    }

    /**
     * 获取前端传递参数
     *
     * @param string $key 参数值
     * @param mixed $default
     * @access public
     * @return mixed
     */
    public function get($key, $default = NULL)
    {
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }

        $arg = $this->getArg($key);
        if (false === $arg) {
            return $default;
        }

        return $arg;
    }

    /**
     * 获取数组化的参数
     *
     * @param mixed $key
     * @access public
     * @return array
     */
    public function getArray($key)
    {
        if (is_array($key)) {
            $result = array();
            foreach ($key as $k) {
                $val = $this->get($k, NULL);
                $result[$k] = $val;
            }
            return $result;
        } else {
            $result = $this->get($key, array());
            return is_array($result) ? $result : array($result);
        }
    }

    /**
     * 判断复杂的参数情况
     *
     * @param mixed $query 前端传递的参数
     * @access public
     * @return boolean
     */
    public function is($query)
    {
        $validated = false;

        /** 解析串 */
        if (is_string($query)) {
            parse_str($query, $params);
        } else if (is_array($query)) {
            $params = $query;
        }

        /** 验证串 */
        if (!empty($params)) {
            $validated = true;
            foreach ($params as $key => $val) {
                $validated = empty($val) ? ($val != $this->get($key)) : ($val == $this->get($key));

                if (!$validated) {
                    break;
                }
            }
        }

        return $validated;
    }

    /**
     * 获取指定的COOKIE值
     *
     * @access public
     * @param string $key 指定的参数
     * @param string $default 默认的参数
     * @return mixed
     */
    public function getCookie($key, $default = NULL)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default;
    }

    /**
     * 获取客户端ip 
     * 
     * @access public
     * @return string
     */
    public function getIp()
    {
        if (empty($this->_ip)) {
            switch (true) {
                case !empty($_SERVER['HTTP_X_FORWARDED_FOR']):
                    list($this->_ip) = array_map('trim', explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
                    break;
                case !empty($_SERVER['HTTP_CLIENT_IP']):
                    $this->_ip = $_SERVER['HTTP_CLIENT_IP'];
                    break;
                case !empty($_SERVER['REMOTE_ADDR']):
                    $this->_ip = $_SERVER['REMOTE_ADDR'];
                    break;
                default:
                    $this->_ip = '-';
                    break;
            }
        }

        return $this->_ip;
    }

    /**
     * 请求方式
     * 
     * @access public
     * @return string
     */
    public function getMethod()
    {
        if (empty($this->_method)) {
            $this->_method = strtoupper($_SERVER['REQUEST_METHOD']);
        }

        return $this->_method;
    }

    /**
     * 获取请求地址
     * 
     * @access public
     * @return string
     */
    public function getRequestUri()
    {
        if (!empty($this->_requestUri)) {
            return $this->_requestUri;
        }

        //处理requestUri
        $requestUri = '/';

        if (isset($_SERVER['HTTP_X_REWRITE_URL'])) { // check this first so IIS will catch
            $requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
        } elseif (
            // IIS7 with URL Rewrite: make sure we get the unencoded url (double slash problem)
            isset($_SERVER['IIS_WasUrlRewritten'])
            && $_SERVER['IIS_WasUrlRewritten'] == '1'
            && isset($_SERVER['UNENCODED_URL'])
            && $_SERVER['UNENCODED_URL'] != ''
            ) {
            $requestUri = $_SERVER['UNENCODED_URL'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
            if (isset($_SERVER['HTTP_HOST']) && strstr($requestUri, $_SERVER['HTTP_HOST'])) {
                $parts       = @parse_url($requestUri);

                if (false !== $parts) {
                    $requestUri  = (empty($parts['path']) ? '' : $parts['path'])
                                 . ((empty($parts['query'])) ? '' : '?' . $parts['query']);
                }
            }
        } elseif (isset($_SERVER['ORIG_PATH_INFO'])) { // IIS 5.0, PHP as CGI
            $requestUri = $_SERVER['ORIG_PATH_INFO'];
            if (!empty($_SERVER['QUERY_STRING'])) {
                $requestUri .= '?' . $_SERVER['QUERY_STRING'];
            }
        }

        return $this->_requestUri = $requestUri;
    }

    /**
     * 获取基础目录
     * 
     * @static
     * @access public
     * @return string
     */
    public function getBaseUrl()
    {
        //处理baseUrl
        if (NULL !== $this->_baseUrl) {
            return $this->_baseUrl;
        }

        $filename = (isset($_SERVER['SCRIPT_FILENAME'])) ? basename($_SERVER['SCRIPT_FILENAME']) : '';

        if (isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === $filename) {
            $baseUrl = $_SERVER['SCRIPT_NAME'];
        } elseif (isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) === $filename) {
            $baseUrl = $_SERVER['PHP_SELF'];
        } elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $filename) {
            $baseUrl = $_SERVER['ORIG_SCRIPT_NAME']; // 1and1 shared hosting compatibility
        } else {
            // Backtrack up the script_filename to find the portion matching
            // php_self
            $path    = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '';
            $file    = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';
            $segs    = explode('/', trim($file, '/'));
            $segs    = array_reverse($segs);
            $index   = 0;
            $last    = count($segs);
            $baseUrl = '';
            do {
                $seg     = $segs[$index];
                $baseUrl = '/' . $seg . $baseUrl;
                ++$index;
            } while (($last > $index) && (false !== ($pos = strpos($path, $baseUrl))) && (0 != $pos));
        }

        // Does the baseUrl have anything in common with the request_uri?
        $finalBaseUrl = NULL;
        $requestUri = $this->getRequestUri();

        if (0 === strpos($requestUri, $baseUrl)) {
            // full $baseUrl matches
            $finalBaseUrl = $baseUrl;
        } else if (0 === strpos($requestUri, dirname($baseUrl))) {
            // directory portion of $baseUrl matches
            $finalBaseUrl = rtrim(dirname($baseUrl), '/');
        } else if (!strpos($requestUri, basename($baseUrl))) {
            // no match whatsoever; set it blank
            $finalBaseUrl = '';
        } else if ((strlen($requestUri) >= strlen($baseUrl))
            && ((false !== ($pos = strpos($requestUri, $baseUrl))) && ($pos !== 0)))
        {
            // If using mod_rewrite or ISAPI_Rewrite strip the script filename
            // out of baseUrl. $pos !== 0 makes sure it is not matching a value
            // from PATH_INFO or QUERY_STRING
            $baseUrl = substr($requestUri, 0, $pos + strlen($baseUrl));
        }

        return ($this->_baseUrl = (NULL === $finalBaseUrl) ? rtrim($baseUrl, '/') : $finalBaseUrl);
    }

    /**
     * getRequestRoot 
     * 
     * @static
     * @access public
     * @return string
     */
    public function getRequestRoot()
    {
        if (NULL === $this->_requestRoot) {
            $root = rtrim(($this->isSecure() ? 'https' : 'http')
                . '://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost')
                . $this->getBaseUrl(), '/') . '/';
            
            $pos = strrpos($root, '.php/');
            if ($pos) {
                $root = dirname(substr($root, 0, $pos));
            }

            $this->_requestRoot = rtrim($root, '/');
        }

        return $this->_requestRoot;
    }

    /**
     * 获取当前pathinfo
     *
     * @access public
     * @return string
     */
    public function getPathInfo()
    {
        /** 缓存信息 */
        if (NULL !== $this->_pathInfo) {
            return $this->_pathInfo;
        }

        //参考Zend Framework对pahtinfo的处理, 更好的兼容性
        $pathInfo = NULL;
        $requestUri = $this->getRequestUri();
        $finalBaseUrl = $this->getBaseUrl();

        // Remove the query string from REQUEST_URI
        if ($pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        if ((NULL !== $finalBaseUrl)
            && (false === ($pathInfo = substr($requestUri, strlen($finalBaseUrl)))))
        {
            // If substr() returns false then PATH_INFO is set to an empty string
            $pathInfo = '/';
        } elseif (NULL === $finalBaseUrl) {
            $pathInfo = $requestUri;
        }

        if (empty($pathInfo)) {
            $pathInfo = '/';
        }

        // fix issue 456
        return ($this->_pathInfo = '/' . ltrim(urldecode($pathInfo), '/'));
    }

    /**
     * getAcceptLangs  
     * 
     * @static
     * @access public
     * @return array
     */
    public function getAcceptLangs()
    {
        if (false == $this->_acceptLangs) {
            $lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'en';
            if (preg_match_all("/[a-z-]+/i", $lang, $matches)) {
                $this->_acceptLangs = array_map('strtolower', $matches[0]);
            } else {
                $this->_acceptLangs = array('en');
            }
        }

        return $this->_acceptLangs;
    }

    /**
     * 获取来源页
     *
     * @access public
     * @return string
     */
    public function getReferer()
    {
        if (false === $this->_referer) {
            $this->_referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
        }

        return $this->_referer;
    }

    /**
     * 判断参数传递是否为POST形式 
     * 
     * @access public
     * @return boolean
     */
    public function isPost()
    {
        return 'POST' == $this->getMethod();
    }

    /**
     * 判断参数传递是否为GET形式 
     * 
     * @access public
     * @return boolean
     */
    public function isGet()
    {
        return 'GET' == $this->getMethod();
    }

    /**
     * 是否为上传模式 
     * 
     * @static
     * @access public
     * @return boolean
     */
    public function isUpload()
    {
        return !empty($_FILES);
    }

    /**
     * 判断是否为ajax
     *
     * @access public
     * @return boolean
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' == $_SERVER['HTTP_X_REQUESTED_WITH'];
    }

    /**
     * 判断是否为flash
     *
     * @access public
     * @return boolean
     */
    public function isFlash()
    {
        return 'Shockwave Flash' == $this->getAgent();
    }

    /**
     * 是否为安全连接 
     * 
     * @access public
     * @return boolean
     */
    public function isSecure()
    {
        return NULL === $this->_isSecure ? ($this->_isSecure = 
            (isset($_SERVER['HTTPS']) && 'on' == $_SERVER['HTTPS']) || (isset($_SERVER['SERVER_PORT']) && 443 == $_SERVER['SERVER_PORT'])) : $this->_isSecure;
    }

    /**
     * isMobile  
     * 
     * @static
     * @access public
     * @return boolean
     */
    public function isMobile()
    {
        if (NULL === $this->_isMobile) {
            $userAgent = $this->getAgent();

            $this->_isMobile = preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$userAgent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($userAgent,0,4));
        }

        return $this->_isMobile;
    }
}

