<?php

use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Http\Request;

test('sets accept header to application/json', function () {
    $request = Request::create('/test', 'GET');
    $middleware = new ForceJsonResponse;

    $middleware->handle($request, fn ($req) => response()->make());

    expect($request->headers->get('Accept'))->toBe('application/json');
});

test('overwrites existing accept header', function () {
    $request = Request::create('/test', 'GET');
    $request->headers->set('Accept', 'text/html');
    $middleware = new ForceJsonResponse;

    $middleware->handle($request, fn ($req) => response()->make());

    expect($request->headers->get('Accept'))->toBe('application/json');
});

test('passes request to next middleware', function () {
    $request = Request::create('/test', 'GET');
    $middleware = new ForceJsonResponse;
    $called = false;

    $middleware->handle($request, function ($req) use (&$called) {
        $called = true;

        return response()->make();
    });

    expect($called)->toBeTrue();
});
