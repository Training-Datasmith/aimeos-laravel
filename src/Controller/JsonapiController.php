<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2017-2023
 */
namespace Aimeos\Shop\Controller;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\Server_Request_Interface;
/**
 * Aimeos controller for the JSON REST API
 */
class Jsonapi_Controller extends Controller
{
    /**
     * Deletes the resource object or a list of resource objects
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function delete_action(Server_Request_Interface $request)
    {
        return $this->create_client()->delete($request, (new Psr17Factory())->create_response());
    }
    /**
     * Returns the requested resource object or list of resource objects
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function get_action(Server_Request_Interface $request)
    {
        return $this->create_client()->get($request, (new Psr17Factory())->create_response());
    }
    /**
     * Updates a resource object or a list of resource objects
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function patch_action(Server_Request_Interface $request)
    {
        return $this->create_client()->patch($request, (new Psr17Factory())->create_response());
    }
    /**
     * Creates a new resource object or a list of resource objects
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function post_action(Server_Request_Interface $request)
    {
        return $this->create_client()->post($request, (new Psr17Factory())->create_response());
    }
    /**
     * Creates or updates a single resource object
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function put_action(Server_Request_Interface $request)
    {
        return $this->create_client()->put($request, (new Psr17Factory())->create_response());
    }
    /**
     * Returns the available HTTP verbs and the resource URLs
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function options_action(Server_Request_Interface $request)
    {
        return $this->create_client()->options($request, (new Psr17Factory())->create_response())->with_header('access-control-allow-headers', 'authorization,content-type')->with_header('access-control-allow-methods', 'DELETE, GET, OPTIONS, PATCH, POST, PUT')->with_header('access-control-allow-origin', $request->get_header_line('origin'));
    }
    /**
     * Returns the JsonAdm client
     *
     * @return \Aimeos\Client\JsonApi\Iface JsonApi client
     */
    protected function create_client(): \Aimeos\Client\Json_Api\Iface
    {
        $resource = Route::input('resource');
        $related = Route::input('related', Request::get('related'));
        $aimeos = app('aimeos')->get();
        $context = app('aimeos.context')->get();
        $tmpl_paths = $aimeos->get_template_paths('client/jsonapi/templates', $context->locale()->get_site_item()->get_theme());
        $langid = $context->locale()->get_language_id();
        $context->set_view(app('aimeos.view')->create($context, $tmpl_paths, $langid));
        return \Aimeos\Client\Json_Api::create($context, $resource . '/' . $related);
    }
}