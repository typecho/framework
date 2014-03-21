<?php

namespace TE\Mvc;

use TE\Mvc\Result\AbstractResult as Result;

/**
 * Response  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Response
{
    /**
     * http code
     *
     * @access private
     * @var array
     */
    private $_httpCode = array(
        100 => 'Continue',
        101	=> 'Switching Protocols',
        200	=> 'OK',
        201	=> 'Created',
        202	=> 'Accepted',
        203	=> 'Non-Authoritative Information',
        204	=> 'No Content',
        205	=> 'Reset Content',
        206	=> 'Partial Content',
        300	=> 'Multiple Choices',
        301	=> 'Moved Permanently',
        302	=> 'Found',
        303	=> 'See Other',
        304	=> 'Not Modified',
        305	=> 'Use Proxy',
        307	=> 'Temporary Redirect',
        400	=> 'Bad Request',
        401	=> 'Unauthorized',
        402	=> 'Payment Required',
        403	=> 'Forbidden',
        404	=> 'Not Found',
        405	=> 'Method Not Allowed',
        406	=> 'Not Acceptable',
        407	=> 'Proxy Authentication Required',
        408	=> 'Request Timeout',
        409	=> 'Conflict',
        410	=> 'Gone',
        411	=> 'Length Required',
        412	=> 'Precondition Failed',
        413	=> 'Request Entity Too Large',
        414	=> 'Request-URI Too Long',
        415	=> 'Unsupported Media Type',
        416	=> 'Requested Range Not Satisfiable',
        417	=> 'Expectation Failed',
        500	=> 'Internal Server Error',
        501	=> 'Not Implemented',
        502	=> 'Bad Gateway',
        503	=> 'Service Unavailable',
        504	=> 'Gateway Timeout',
        505	=> 'HTTP Version Not Supported'
    );

    /**
     * _cookies  
     * 
     * @var array
     * @access private
     */
    private $_cookies = array();

    /**
     * _statusCode
     * 
     * @var float
     * @access private
     */
    private $_statusCode = 200;

    /**
     * _charset  
     * 
     * @var string
     * @access private
     */
    private $_charset = 'UTF-8';

    /**
     * _contentType  
     * 
     * @var string
     * @access private
     */
    private $_contentType = 'text/html';

    /**
     * _headers  
     * 
     * @var array
     * @access private
     */
    private $_headers = array();

    /**
     * _result
     * 
     * @var Result
     * @access private
     */
    private $_result;

    /**
     * respond  
     * 
     * @access public
     */
    public function respond()
    {
        // set status code
        header('HTTP/1.1 ' . $this->_statusCode . ' ' . $this->_httpCode[$this->_statusCode], true, $this->_statusCode);

        // set content type
        header('Content-Type: ' . $this->_contentType . '; charset=' . $this->_charset);

        // set header
        foreach ($this->_headers as $header) {
            header($header, true);
        }

        // set cookie
        foreach ($this->_cookies as $cookie) {
            list ($key, $value, $timeout, $path, $domain) = $cookie;

            if ($timeout > 0) {
                $timeout += time();
            } else if ($timeout < 0) {
                $timeout = 1;
            }

            setCookie($key, $value, $timeout, $path, $domain);
        }

        if (NULL !== $this->_result) {
            $this->_result->render();
        }
    }

    /**
     * 设置指定的COOKIE值
     *
     * @access public
     * @param string $key 指定的参数
     * @param mixed $value 设置的值
     * @param integer $timeout 过期时间,默认为0,表示随会话时间结束
     * @param string $path 路径信息
     * @param string $domain 域名信息
     * @return Response
     */
    public function setCookie($key, $value, $timeout = 0, $path = '/', $domain = NULL)
    {
        if (is_array($value)) {
            foreach ($value as $name => $val) {
                $this->_cookies[] = array("{$key}[{$name}]", $val, $timeout, $path, $domain);
            }
        } else {
            $this->_cookies[] = array($key, $value, $timeout, $path, $domain);
        }

        return $this;
    }

    /**
     * 删除指定的COOKIE值
     *
     * @access public
     * @param string $key 指定的参数
     * @param string $path 路径信息
     * @param string $domain 域名信息
     * @return Response
     */
    public function deleteCookie($key, $path = '/', $domain = NULL)
    {
        if (!isset($_COOKIE[$key])) {
            return;
        }

        if (is_array($_COOKIE[$key])) {
            foreach ($_COOKIE[$key] as $name => $val) {
                $this->_cookies[] = array("{$key}[{$name}]", '', -1, $path, $domain);
            }
        } else {
            $this->_cookies[] = array($key, '', -1, $path, $domain);
        }

        return $this;
    }

    /**
     * 静态页面跳转
     * 
     * @param mixed $url 
     * @access public
     * @return Response
     */
    public function setPermanentlyRedirection($url)
    {
        $this->setStatusCode(301);
        $this->setHeader('Location', $url);
        return $this;
    }

    /**
     * 动态页面跳转 
     * 
     * @param mixed $url 
     * @access public
     * @return Response
     */
    public function setTemporarilyRedirection($url)
    {
        $this->setStatusCode(302);
        $this->setHeader('Location', $url);
        return $this;
    }

    /**
     * setContentType  
     * 
     * @param mixed $contentType 
     * @access public
     * @return Response
     */
    public function setContentType($contentType)
    {
        $this->_contentType = $contentType;
        return $this;
    }

    /**
     * setCharset  
     * 
     * @param mixed $charset 
     * @access public
     * @return Response
     */
    public function setCharset($charset)
    {
        $this->_charset = $charset;
        return $this;
    }

    /**
     * setStatusCode  
     * 
     * @param mixed $statusCode 
     * @access public
     * @return Response
     */
    public function setStatusCode($statusCode)
    {
        $this->_statusCode = $statusCode;
        return $this;
    }

    /**
     * setHeader  
     * 
     * @param mixed $name 
     * @param mixed $value 
     * @access public
     * @return Response
     */
    public function setHeader($name, $value)
    {
        $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
        $this->_headers[] = $name . ': ' . $value;
        return $this;
    }

    /**
     * setResult
     * 
     * @param mixed $result
     * @access public
     * @return Response
     */
    public function setResult(Result $result)
    {
        $result->prepareResponse($this);
        $this->_result = $result;
        return $this;
    }
}

