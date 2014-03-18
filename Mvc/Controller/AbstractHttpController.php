<?php
/**
 * AbstractHttpController.php
 *
 * @author joyqi
 * @date 2013-11-28 14:00
 * @project framework
 */

namespace TE\Mvc\Controller;


use TE\Mvc\Controller\Interceptor\InterceptorManager;
use TE\Mvc\Server\Http\Request;
use TE\Mvc\Server\Http\Response;

/**
 * Class AbstractHttpController
 * @package TE\Mvc\Controller
 */
abstract class AbstractHttpController extends AbstractController
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
 