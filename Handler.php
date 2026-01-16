<?php

//declare(strict_types=1);

namespace cryodrift\quicklinks;

use cryodrift\fw\Context;
use cryodrift\fw\HtmlUi;

class Handler implements \cryodrift\fw\interface\Handler
{

    public function __construct(protected Web $web)
    {
    }

    public function handle(Context $ctx): Context
    {
        $content = $ctx->response()->getContent();
        if ($content instanceof HtmlUi) {
            $ui = $this->web->show(clone $ctx)->response()->getContent();
            $content->setAttributes(['quicklinks' => $ui]);
            $content->setAttributes(['quicklinks2' => str_replace('id="quicklinks" class="{{g-cont}}', 'id="quicklinks" class="{{g-cont}} g-phc', $ui)], false, false);
        }
        return $ctx;
    }

}
