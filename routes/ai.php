<?php

// use App\Mcp\Servers\WeatherExample;
use App\Mcp\Servers\DemoServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::oauthRoutes();

// Mcp::web('/mcp/weather', WeatherExample::class)
//     ->middleware('auth:api');

Mcp::web('/mcp/demo', serverClass: DemoServer::class);
if (app()->environment('local')) {
    Mcp::local('demo', serverClass: DemoServer::class);
}
