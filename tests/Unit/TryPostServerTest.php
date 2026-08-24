<?php

declare(strict_types=1);

use App\Mcp\Servers\TryPostServer;
use Laravel\Mcp\Server\Attributes\Icon;

it("does not expose the upstream TryPost icon after the rebrand", function () {
    $icons = (new ReflectionClass(TryPostServer::class))->getAttributes(Icon::class);

    expect($icons)->toBeEmpty();
});
