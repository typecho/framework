<?php

namespace TE\Mvc\Result;

use TE\Mvc\Server\ResponseInterface as Response;

/**
 * 模板
 * 
 * @uses AbstractResult
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Template extends AbstractResult
{
    /**
     * prepareResponse  
     * 
     * @param Response $response 
     * @access public
     * @return void
     */
    public function prepareResponse(Response $response)
    {}

    /**
     * @throws \Exception
     */
    public function render()
    {
        global $template;

        $_file = $this->getParam(0);
        $_data = $this->getEvent()->getData();
        $_prefix = $this->getParam(1, '');

        $template = function ($_file, array $_merge = NULL) use ($_data, $_prefix) {
            global $template;

            if (!empty($_merge)) {
                $_data = array_merge($_data, $_merge);
            }

            extract($_data);
            $_files = is_array($_file) ? $_file : array($_file);

            foreach ($_files as $_file) {
                $_file = $_prefix . '/' . $_file;
                if (file_exists($_file)) {
                    require $_file;
                    return;
                }
            }

            throw new \Exception('Template file not found');
        };

        $template($_file);
    }
}

