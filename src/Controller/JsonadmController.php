<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */
namespace Aimeos\Shop\Controller;

use Illuminate\Foundation\Auth\Access\Authorizes_Requests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\Server_Request_Interface;
/**
 * Aimeos controller for the JSON REST API
 */
class Jsonadm_Controller extends Controller
{
    use Authorizes_Requests;
    /**
     * Deletes the resource object or a list of resource objects
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function delete_action(Server_Request_Interface $request)
    {
        if (config('shop.authorize', true)) {
            $this->authorize('admin', [Jsonadm_Controller::class, array_merge(config('shop.roles', ['admin', 'editor']), ['api'])]);
        }
        return $this->create_admin()->delete($request, (new Psr17Factory())->create_response());
    }
    /**
     * Returns the requested resource object or list of resource objects
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function get_action(Server_Request_Interface $request)
    {
        if (config('shop.authorize', true)) {
            $this->authorize('admin', [Jsonadm_Controller::class, array_merge(config('shop.roles', ['admin', 'editor']), ['api'])]);
        }
        return $this->create_admin()->get($request, (new Psr17Factory())->create_response());
    }
    /**
     * Updates a resource object or a list of resource objects
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function patch_action(Server_Request_Interface $request)
    {
        if (config('shop.authorize', true)) {
            $this->authorize('admin', [Jsonadm_Controller::class, array_merge(config('shop.roles', ['admin', 'editor']), ['api'])]);
        }
        return $this->create_admin()->patch($request, (new Psr17Factory())->create_response());
    }
    /**
     * Creates a new resource object or a list of resource objects
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function post_action(Server_Request_Interface $request)
    {
        if (config('shop.authorize', true)) {
            $this->authorize('admin', [Jsonadm_Controller::class, array_merge(config('shop.roles', ['admin', 'editor']), ['api'])]);
        }
        return $this->create_admin()->post($request, (new Psr17Factory())->create_response());
    }
    /**
     * Creates or updates a single resource object
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function put_action(Server_Request_Interface $request)
    {
        if (config('shop.authorize', true)) {
            $this->authorize('admin', [Jsonadm_Controller::class, array_merge(config('shop.roles', ['admin', 'editor']), ['api'])]);
        }
        return $this->create_admin()->put($request, (new Psr17Factory())->create_response());
    }
    /**
     * Returns the available HTTP verbs and the resource URLs
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function options_action(Server_Request_Interface $request)
    {
        if (config('shop.authorize', true)) {
            $this->authorize('admin', [Jsonadm_Controller::class, array_merge(config('shop.roles', ['admin', 'editor']), ['api'])]);
        }
        return $this->create_admin()->options($request, (new Psr17Factory())->create_response());
    }
    /**
     * Returns the JsonAdm client
     *
     * @return \Aimeos\Admin\JsonAdm\Iface JsonAdm client
     */
    protected function create_admin(): \Aimeos\Admin\Json_Adm\Iface
    {
        $site = Route::input('site', Request::get('site', config('shop.mshop.locale.site', 'default')));
        $lang = Request::get('locale', config('app.locale', 'en'));
        $resource = Route::input('resource', '');
        $aimeos = app('aimeos')->get();
        $context = app('aimeos.context')->get(false, 'backend');
        $context->set_i18n(app('aimeos.i18n')->get([$lang, 'en']));
        $context->set_locale(app('aimeos.locale')->get_backend($context, $site));
        $template_paths = $aimeos->get_template_paths('admin/jsonadm/templates', $context->locale()->get_site_item()->get_theme());
        $context->set_view(app('aimeos.view')->create($context, $template_paths, $lang));
        return \Aimeos\Admin\Json_Adm::create($context, $aimeos, $resource);
    }
}