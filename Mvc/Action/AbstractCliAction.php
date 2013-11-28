<?php
/**
 * AbstractCliAction.php
 *
 * @author joyqi
 * @date 2013-11-28 14:08
 * @project framework
 */

namespace TE\Mvc\Action;


use TE\Mvc\Action\Interceptor\InterceptorManager;
use TE\Mvc\Server\Cli\Request;
use TE\Mvc\Server\Cli\Response;

/**
 * Class AbstractCliAction
 * @package TE\Mvc\Action
 */
abstract class AbstractCliAction extends AbstractAction
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param Request $request
     * @param Response $response
     * @param InterceptorManager $interceptorManager
     */
    public final function __construct(Request $request, Response $response, InterceptorManager $interceptorManager)
    {
        parent::__construct($request, $response, $interceptorManager);
    }
}
 