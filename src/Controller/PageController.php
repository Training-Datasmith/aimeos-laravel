<?php

declare(strict_types=1);

/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2023
 */

namespace Aimeos\Shop\Controller;

use Aimeos\Shop\Facades\Shop;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

/**
 * Aimeos controller for support page request.
 */
class PageController extends Controller
{
    /**
     * Returns the html for the content pages.
     *
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function indexAction()
    {
        $params = ['page' => 'page-index'];

        foreach (app('config')->get('shop.page.cms', ['cms/page', 'catalog/tree', 'basket/mini']) as $name) {
            $params['aiheader'][$name] = (new Shop())->get()->header();
            $params['aibody'][$name] = (new Shop())->get()->body();
        }

        if (empty($params['aibody']['cms/page'])) {
            abort(404);
        }

        return Response::view(Shop::template('page.index'), $params)
            ->header('Cache-Control', 'private, max-age=10');
    }
}
