<?php

//declare(strict_types=1);

namespace cryodrift\quicklinks;

use cryodrift\fw\cli\Colors;
use cryodrift\fw\Config;
use cryodrift\fw\Context;
use cryodrift\fw\Core;
use cryodrift\fw\HtmlUi;
use cryodrift\fw\Path;
use cryodrift\fw\trait\WebHandler;

class Web
{
    use WebHandler;

    public function __construct(protected Cli $cli, protected Config $config)
    {
    }

    public function handle(Context $ctx): Context
    {
        return $this->handleWeb($ctx);
    }


    /**
     * @web
     */
    public function add(Context $ctx): Context
    {
        if ($ctx->request()->isPost()) {
            $url = trim($ctx->request()->vars('referer'), '"');
            $parts = parse_url($url);
            $path = $parts['path'];
            $this->cli->set($path . '?' . $parts['query'], $path);
        }

        return $this->show($ctx);
    }

    /**
     * @web
     */
    public function upd(Context $ctx): Context
    {
        if ($ctx->request()->isPost()) {
            $values=Core::jsonRead($ctx->request()->vars('value', '[]', true));
//            Core::echo(__METHOD__,$ctx->request()->vars('value'),$values);
            foreach ($values as $pos => $value) {
                $id = array_key_first($value);
                $name = array_pop($value);
                $data = $this->cli->get($id);

                $this->cli->set($data['url'], $name, $pos);
            }
        }
        return $this->show($ctx);
    }

    /**
     * @web
     */
    protected function sort(Context $ctx): Context
    {
        foreach (Core::jsonRead($ctx->request()->vars('id', '[]', true)) as $key => $value) {
            $id = Core::pop(explode('_', Core::getValue('id', $value)));
            Core::echo(__METHOD__, $id);
            $data = $this->cli->get($id);
            $this->cli->set($data['url'], $data['name'], $key + 1);
        }
        return $this->show($ctx);
    }

    /**
     * @web
     */
    protected function rem(Context $ctx): Context
    {
        if ($ctx->request()->isPost()) {
            $id = Core::getValue('name', Core::pop(Core::jsonRead($ctx->request()->vars('name', '[]', true))));
            $this->cli->rem($id);
        }
//        Core::echo(__METHOD__, $id);
        return $this->show($ctx);
    }

    /**
     * @web get Ui
     * @web param: command (edit|save|)
     */
    public function show(Context $ctx, string $command = ''): Context
    {
        $linklist = $this->cli->list();
        switch ($command) {
            case'edit':
                $ui = HtmlUi::fromFile('quicklinks/ui/list.html');
                $list = HtmlUi::fromFile('quicklinks/ui/item-edit.html');
                break;
            case'save':
                $ui = HtmlUi::fromFile('quicklinks/ui/list.html');
                $list = HtmlUi::fromFile('quicklinks/ui/item-show.html');
                break;
            default:
                $ui = HtmlUi::fromFile('quicklinks/ui/main.html');
                $list = HtmlUi::fromFile('quicklinks/ui/item-show.html');
        }
        // add the template to the linklist
        $linklist = array_map(static function ($v) use ($list) {
            return is_array($v) ? array_merge(['item' => $list], $v) : $v;
        }, $linklist);

        $ui->setAttributes(['linklist' => $linklist], false, true, ['url', 'item']);
        $ctx->response()->setContent($ui);
        return $ctx;
    }
}
