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
 * Aimeos controller for catalog related functionality.
 */
class Catalog_Controller extends Controller
{
    /**
     * Returns the view for the XHR response with the counts for the facetted search.
     *
     * @return \Illuminate\Http\Response Response object with output and headers
     */
    public function count_action()
    {
        $params = ['page' => 'page-catalog-count'];
        foreach (app('config')->get('shop.page.catalog-count') as $name) {
            $params['aiheader'][$name] = (new Shop())->get()->header();
            $params['aibody'][$name] = (new Shop())->get()->body();
        }
        return Response::view(Shop::template('catalog.count'), $params)->header('Content-Type', 'application/javascript')->header('Cache-Control', 'public, max-age=300');
    }
    /**
     * Returns the html for the catalog detail page.
     *
     * @return \Illuminate\Http\Response Response object with output and headers
     */
    public function detail_action()
    {
        try {
            $params = ['page' => 'page-catalog-detail'];
            foreach (app('config')->get('shop.page.catalog-detail') as $name) {
                $params['aiheader'][$name] = (new Shop())->get()->header();
                $params['aibody'][$name] = (new Shop())->get()->body();
            }
            return Response::view(Shop::template('catalog.detail'), $params)->header('Cache-Control', 'private, max-age=' . config('shop.cache_maxage', 30));
        } catch (\Exception $e) {
            if ($e->get_code() >= 400 && $e->get_code() < 600) {
                abort($e->get_code());
            }
            throw $e;
        }
    }
    /**
     * Returns the html for the catalog home page.
     *
     * @return \Illuminate\Http\Response Response object with output and headers
     */
    public function home_action()
    {
        $params = ['page' => 'page-catalog-home'];
        foreach (app('config')->get('shop.page.catalog-home') as $name) {
            $params['aiheader'][$name] = (new Shop())->get()->header();
            $params['aibody'][$name] = (new Shop())->get()->body();
        }
        return Response::view(Shop::template('catalog.home'), $params)->header('Cache-Control', 'private, max-age=' . config('shop.cache_maxage', 30));
    }
    /**
     * Returns the html for the catalog list page.
     *
     * @return \Illuminate\Http\Response Response object with output and headers
     */
    public function list_action()
    {
        try {
            $params = ['page' => 'page-catalog-list'];
            foreach (app('config')->get('shop.page.catalog-list') as $name) {
                $params['aiheader'][$name] = (new Shop())->get()->header();
                $params['aibody'][$name] = (new Shop())->get()->body();
            }
            return Response::view(Shop::template('catalog.list'), $params)->header('Cache-Control', 'private, max-age=' . config('shop.cache_maxage', 30));
        } catch (\Exception $e) {
            if ($e->get_code() >= 400 && $e->get_code() < 600) {
                abort($e->get_code());
            }
            throw $e;
        }
    }
    /**
     * Returns the html for the catalog session page.
     *
     * @return \Illuminate\Http\Response Response object with output and headers
     */
    public function session_action()
    {
        $params = ['page' => 'page-catalog-session'];
        foreach (app('config')->get('shop.page.catalog-session') as $name) {
            $params['aiheader'][$name] = (new Shop())->get()->header();
            $params['aibody'][$name] = (new Shop())->get()->body();
        }
        return Response::view(Shop::template('catalog.session'), $params)->header('Cache-Control', 'no-cache');
    }
    /**
     * Returns the html body part for the catalog stock page.
     *
     * @return \Illuminate\Http\Response Response object with output and headers
     */
    public function stock_action()
    {
        $params = ['page' => 'page-catalog-stock'];
        foreach (app('config')->get('shop.page.catalog-stock') as $name) {
            $params['aiheader'][$name] = (new Shop())->get()->header();
            $params['aibody'][$name] = (new Shop())->get()->body();
        }
        return Response::view(Shop::template('catalog.stock'), $params)->header('Content-Type', 'application/javascript')->header('Cache-Control', 'public, max-age=30');
    }
    /**
     * Returns the view for the XHR response with the product information for the search suggestion.
     *
     * @return \Illuminate\Http\Response Response object with output and headers
     */
    public function suggest_action()
    {
        $params = ['page' => 'page-catalog-suggest'];
        foreach (app('config')->get('shop.page.catalog-suggest') as $name) {
            $params['aiheader'][$name] = (new Shop())->get()->header();
            $params['aibody'][$name] = (new Shop())->get()->body();
        }
        return Response::view(Shop::template('catalog.suggest'), $params)->header('Cache-Control', 'private, max-age=' . config('shop.cache_maxage', 30))->header('Content-Type', 'application/json');
    }
    /**
     * Returns the html for the catalog tree page.
     *
     * @return \Illuminate\Http\Response Response object with output and headers
     */
    public function tree_action()
    {
        try {
            $params = ['page' => 'page-catalog-tree'];
            foreach (app('config')->get('shop.page.catalog-tree') as $name) {
                $params['aiheader'][$name] = (new Shop())->get()->header();
                $params['aibody'][$name] = (new Shop())->get()->body();
            }
            return Response::view(Shop::template('catalog.tree'), $params)->header('Cache-Control', 'private, max-age=' . config('shop.cache_maxage', 30));
        } catch (\Exception $e) {
            if ($e->get_code() >= 400 && $e->get_code() < 600) {
                abort($e->get_code());
            }
            throw $e;
        }
    }
}