<?php

declare (strict_types=1);
/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */
namespace Aimeos\Shop\Controller;

use Aimeos\Shop\Facades\Shop;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
/**
 * Aimeos controller for account related functionality.
 */
class Account_Controller extends Controller
{
    /**
     * Returns the html for the "My account" page.
     *
     * @return \Illuminate\Http\Response Response object with output and headers
     */
    public function index_action()
    {
        $params = ['page' => 'page-account-index'];
        foreach (app('config')->get('shop.page.account-index') as $name) {
            $params['aiheader'][$name] = (new Shop())->get()->header();
            $params['aibody'][$name] = (new Shop())->get()->body();
        }
        return Response::view(Shop::template('account.index'), $params)->header('Cache-Control', 'no-store, max-age=0');
    }
    /**
     * Returns the html for the "My account" download page.
     *
     * @return \Illuminate\Contracts\View\View View for rendering the output
     */
    public function download_action()
    {
        $response = (new Shop())->get()->response();
        return Response::make((string) $response->get_body(), $response->get_status_code(), $response->get_headers());
    }
}