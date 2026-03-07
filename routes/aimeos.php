<?php

declare(strict_types=1);

if (($conf = config('shop.routes.admin', ['prefix' => 'admin', 'middleware' => ['web']])) !== false) {

    Route::group($conf, function () {

        Route::match([ 'GET' ], '', [
            'as' => 'aimeos_shop_admin',
            'uses' => 'Aimeos\Shop\Controller\AdminController@indexAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

    });
}

if (($conf = config('shop.routes.jqadm', ['prefix' => 'admin/{site}/jqadm', 'middleware' => ['web', 'auth']])) !== false) {

    Route::group($conf, function () {

        Route::match([ 'GET' ], 'file/{name}/{locale}', [
            'as' => 'aimeos_shop_jqadm_file',
            'uses' => 'Aimeos\Shop\Controller\JqadmController@fileAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'POST' ], 'batch/{resource}', [
            'as' => 'aimeos_shop_jqadm_batch',
            'uses' => 'Aimeos\Shop\Controller\JqadmController@batchAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

        Route::match([ 'GET', 'POST' ], 'copy/{resource}/{id}', [
            'as' => 'aimeos_shop_jqadm_copy',
            'uses' => 'Aimeos\Shop\Controller\JqadmController@copyAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

        Route::match([ 'GET', 'POST' ], 'create/{resource}', [
            'as' => 'aimeos_shop_jqadm_create',
            'uses' => 'Aimeos\Shop\Controller\JqadmController@createAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

        Route::match([ 'POST' ], 'delete/{resource}/{id?}', [
            'as' => 'aimeos_shop_jqadm_delete',
            'uses' => 'Aimeos\Shop\Controller\JqadmController@deleteAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

        Route::match([ 'GET', 'POST' ], 'export/{resource}', [
            'as' => 'aimeos_shop_jqadm_export',
            'uses' => 'Aimeos\Shop\Controller\JqadmController@exportAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

        Route::match([ 'GET' ], 'get/{resource}/{id}', [
            'as' => 'aimeos_shop_jqadm_get',
            'uses' => 'Aimeos\Shop\Controller\JqadmController@getAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

        Route::match([ 'POST' ], 'import/{resource}', [
            'as' => 'aimeos_shop_jqadm_import',
            'uses' => 'Aimeos\Shop\Controller\JqadmController@importAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

        Route::match([ 'POST' ], 'save/{resource}', [
            'as' => 'aimeos_shop_jqadm_save',
            'uses' => 'Aimeos\Shop\Controller\JqadmController@saveAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

        Route::match([ 'GET', 'POST' ], 'search/{resource}', [
            'as' => 'aimeos_shop_jqadm_search',
            'uses' => 'Aimeos\Shop\Controller\JqadmController@searchAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

    });
}

if (($conf = config('shop.routes.graphql', ['prefix' => 'admin/{site}/graphql', 'middleware' => ['web', 'auth']])) !== false) {

    Route::group($conf, function () {

        Route::match([ 'POST' ], '', [
            'as' => 'aimeos_shop_graphql_post',
            'uses' => 'Aimeos\Shop\Controller\GraphqlController@indexAction',
        ])->where(['site' => '[A-Za-z0-9\.\-]+']);

    });
}

if (($conf = config('shop.routes.jsonadm', ['prefix' => 'admin/{site}/jsonadm', 'middleware' => ['web', 'auth']])) !== false) {

    Route::group($conf, function () {

        Route::match([ 'DELETE' ], '{resource}/{id?}', [
            'as' => 'aimeos_shop_jsonadm_delete',
            'uses' => 'Aimeos\Shop\Controller\JsonadmController@deleteAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

        Route::match([ 'GET' ], '{resource}/{id?}', [
            'as' => 'aimeos_shop_jsonadm_get',
            'uses' => 'Aimeos\Shop\Controller\JsonadmController@getAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

        Route::match([ 'PATCH' ], '{resource}/{id?}', [
            'as' => 'aimeos_shop_jsonadm_patch',
            'uses' => 'Aimeos\Shop\Controller\JsonadmController@patchAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

        Route::match([ 'POST' ], '{resource}/{id?}', [
            'as' => 'aimeos_shop_jsonadm_post',
            'uses' => 'Aimeos\Shop\Controller\JsonadmController@postAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

        Route::match([ 'PUT' ], '{resource}/{id?}', [
            'as' => 'aimeos_shop_jsonadm_put',
            'uses' => 'Aimeos\Shop\Controller\JsonadmController@putAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

        Route::match([ 'OPTIONS' ], '{resource?}', [
            'as' => 'aimeos_shop_jsonadm_options',
            'uses' => 'Aimeos\Shop\Controller\JsonadmController@optionsAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'resource' => '[a-z\/]+']);

    });
}

if (($conf = config('shop.routes.jsonapi', ['prefix' => 'jsonapi', 'middleware' => ['web', 'api']])) !== false) {

    Route::group($conf, function () {

        Route::match([ 'DELETE' ], '{resource}', [
            'as' => 'aimeos_shop_jsonapi_delete',
            'uses' => 'Aimeos\Shop\Controller\JsonapiController@deleteAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'GET' ], '{resource}', [
            'as' => 'aimeos_shop_jsonapi_get',
            'uses' => 'Aimeos\Shop\Controller\JsonapiController@getAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'PATCH' ], '{resource}', [
            'as' => 'aimeos_shop_jsonapi_patch',
            'uses' => 'Aimeos\Shop\Controller\JsonapiController@patchAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'POST' ], '{resource}', [
            'as' => 'aimeos_shop_jsonapi_post',
            'uses' => 'Aimeos\Shop\Controller\JsonapiController@postAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'PUT' ], '{resource}', [
            'as' => 'aimeos_shop_jsonapi_put',
            'uses' => 'Aimeos\Shop\Controller\JsonapiController@putAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'GET', 'OPTIONS' ], '{resource?}', [
            'as' => 'aimeos_shop_jsonapi_options',
            'uses' => 'Aimeos\Shop\Controller\JsonapiController@optionsAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

    });
}

if (($conf = config('shop.routes.account', ['prefix' => 'profile', 'middleware' => ['web', 'auth']])) !== false) {

    Route::group($conf, function () {

        Route::match([ 'GET', 'POST' ], 'favorite/{fav_action?}/{fav_id?}/{d_name?}/{d_pos?}', [
            'as' => 'aimeos_shop_account_favorite',
            'uses' => 'Aimeos\Shop\Controller\AccountController@indexAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'GET', 'POST' ], 'watch/{wat_action?}/{wat_id?}/{d_name?}/{d_pos?}', [
            'as' => 'aimeos_shop_account_watch',
            'uses' => 'Aimeos\Shop\Controller\AccountController@indexAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'GET', 'POST' ], 'download/{dl_id}', [
            'as' => 'aimeos_shop_account_download',
            'uses' => 'Aimeos\Shop\Controller\AccountController@downloadAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'GET', 'POST' ], '', [
            'as' => 'aimeos_shop_account',
            'uses' => 'Aimeos\Shop\Controller\AccountController@indexAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

    });
}

if (($conf = config('shop.routes.supplier', ['prefix' => 'brand', 'middleware' => ['web']])) !== false) {

    Route::group($conf, function () {

        Route::match([ 'GET', 'POST' ], '{s_name}/{f_supid}', [
            'as' => 'aimeos_shop_supplier',
            'uses' => 'Aimeos\Shop\Controller\SupplierController@detailAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

    });
}

if (($conf = config('shop.routes.update', [])) !== false) {

    Route::group($conf, function () {

        Route::match([ 'GET', 'POST' ], 'update', [
            'as' => 'aimeos_shop_update',
            'uses' => 'Aimeos\Shop\Controller\CheckoutController@updateAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

    });
}

if (($conf = config('shop.routes.confirm', ['prefix' => 'shop', 'middleware' => ['web']])) !== false) {

    Route::group($conf, function () {

        Route::match([ 'GET', 'POST' ], 'confirm/{code?}', [
            'as' => 'aimeos_shop_confirm',
            'uses' => 'Aimeos\Shop\Controller\CheckoutController@confirmAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

    });
}

if (($conf = config('shop.routes.checkout', ['prefix' => 'shop', 'middleware' => ['web']])) !== false) {

    Route::group($conf, function () {

        Route::match([ 'GET', 'POST' ], 'checkout/{c_step?}', [
            'as' => 'aimeos_shop_checkout',
            'uses' => 'Aimeos\Shop\Controller\CheckoutController@indexAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

    });
}

if (($conf = config('shop.routes.basket', ['prefix' => 'shop', 'middleware' => ['web']])) !== false) {

    Route::group($conf, function () {

        Route::match([ 'GET', 'POST' ], 'basket', [
            'as' => 'aimeos_shop_basket',
            'uses' => 'Aimeos\Shop\Controller\BasketController@indexAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

    });
}

if (($conf = config('shop.routes.default', ['prefix' => 'shop', 'middleware' => ['web']])) !== false) {

    Route::group($conf, function () {

        Route::match([ 'GET', 'POST' ], 'count', [
            'as' => 'aimeos_shop_count',
            'uses' => 'Aimeos\Shop\Controller\CatalogController@countAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'GET', 'POST' ], 'suggest', [
            'as' => 'aimeos_shop_suggest',
            'uses' => 'Aimeos\Shop\Controller\CatalogController@suggestAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'GET', 'POST' ], 'stock', [
            'as' => 'aimeos_shop_stock',
            'uses' => 'Aimeos\Shop\Controller\CatalogController@stockAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'GET', 'POST' ], 'pin', [
            'as' => 'aimeos_shop_session_pinned',
            'uses' => 'Aimeos\Shop\Controller\CatalogController@sessionAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'GET', 'POST' ], 'search', [
            'as' => 'aimeos_shop_list',
            'uses' => 'Aimeos\Shop\Controller\CatalogController@listAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

        Route::match([ 'GET', 'POST' ], '{f_name}~{f_catid}/{l_page?}', [
            'as' => 'aimeos_shop_tree',
            'uses' => 'Aimeos\Shop\Controller\CatalogController@treeAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'f_name' => '[^~]*', 'l_page' => '[0-9]+']);

        Route::match([ 'GET', 'POST' ], '{d_name}/{d_pos?}/{d_prodid?}', [
            'as' => 'aimeos_shop_detail',
            'uses' => 'Aimeos\Shop\Controller\CatalogController@detailAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+', 'd_pos' => '[0-9]*']);

    });
}

if (($conf = config('shop.routes.page', ['prefix' => 'p', 'middleware' => ['web']])) !== false) {

    Route::group($conf, function () {

        Route::match(['GET', 'POST'], '{path?}', [
            'as' => 'aimeos_page',
            'uses' => '\Aimeos\Shop\Controller\PageController@indexAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);
    });
}

if (($conf = config('shop.routes.home', ['middleware' => ['web']])) !== false) {

    Route::group($conf, function () {

        Route::match([ 'GET', 'POST' ], '/', [
            'as' => 'aimeos_home',
            'uses' => 'Aimeos\Shop\Controller\CatalogController@homeAction',
        ])->where(['locale' => '[a-z]{2}(\_[A-Z]{2})?', 'site' => '[A-Za-z0-9\.\-]+']);

    });
}
