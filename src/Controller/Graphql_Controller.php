<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2022-2023
 */
namespace Aimeos\Shop\Controller;

use Illuminate\Foundation\Auth\Access\Authorizes_Requests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Psr\Http\Message\Server_Request_Interface;
/**
 * Aimeos controller for the GraphQL Admin API
 */
class Graphql_Controller extends Controller
{
    use Authorizes_Requests;
    /**
     * Creates a new resource object or a list of resource objects
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function index_action(Server_Request_Interface $request)
    {
        if (config('shop.authorize', true)) {
            $this->authorize('admin', [Graphql_Controller::class, array_merge(config('shop.roles', ['admin', 'editor']), ['api'])]);
        }
        $site = Route::input('site', Request::get('site', config('shop.mshop.locale.site', 'default')));
        $lang = Request::get('locale', config('app.locale', 'en'));
        $context = app('aimeos.context')->get(false, 'backend');
        $context->set_i18n(app('aimeos.i18n')->get([$lang, 'en']));
        $context->set_locale(app('aimeos.locale')->get_backend($context, $site));
        $context->set_view(app('aimeos.view')->create($context, [], $lang));
        return \Aimeos\Admin\Graphql::execute($context, $request);
    }
}