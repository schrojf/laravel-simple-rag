<?php

// use App\Mcp\Servers\WeatherExample;
use Laravel\Mcp\Facades\Mcp;

Mcp::oauthRoutes();

// Mcp::web('/mcp/weather', WeatherExample::class)
//     ->middleware('auth:api');
