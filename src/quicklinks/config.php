<?php

//declare(strict_types=1);


/**
 * @env USER_STORAGEDIRS="G_ROOTDIR.cryodrift/users/"
 */

use cryodrift\fw\Core;

if (!isset($ctx)) {
    $ctx = Core::newContext(new \cryodrift\fw\Config());
}

$cfg = $ctx->config();

$cfg[\cryodrift\quicklinks\db\Repository::class] = [
  'storagedir' => Core::env('USER_STORAGEDIRS')
];

\cryodrift\fw\Router::addConfigs($ctx, [
  'quicklinks/cli' => \cryodrift\quicklinks\Cli::class,
], \cryodrift\fw\Router::TYP_CLI);

\cryodrift\fw\Router::addConfigs($ctx, [
  'quicklinks' => \cryodrift\quicklinks\Web::class,
], \cryodrift\fw\Router::TYP_WEB);
