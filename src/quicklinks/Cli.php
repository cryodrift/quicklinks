<?php

//declare(strict_types=1);

namespace cryodrift\quicklinks;

use cryodrift\quicklinks\db\Repository;
use cryodrift\fw\Context;
use cryodrift\fw\Core;
use cryodrift\fw\interface\Installable;
use cryodrift\fw\trait\CliHandler;

class Cli implements Installable
{
    use CliHandler;


    public function __construct(protected Repository $db)
    {
    }

    public function handle(Context $ctx): Context
    {
        $ctx->response()->setStatusFinal();
        return $this->handleCli($ctx);
    }

    /**
     * @cli add or mod entry
     */
    public function set(string $url, string $name, int $id = 0, int $sortnum = 0): string
    {
        try {
            if ($id) {
                $this->db->runInsert(Repository::TABLE, Repository::COLUMNS, ['id' => $id, 'url' => $url, 'name' => $name, 'sortnum' => $sortnum]);
            } else {
                $this->db->runInsert(Repository::TABLE, Repository::COLUMNS, ['url' => $url, 'name' => $name, 'sortnum' => $sortnum]);
            }
            return 'OK';
        } catch (\Exception $ex) {
            Core::echo(__METHOD__, $ex);
            return 'FAIL';
        }
    }

    /**
     * @cli delete entry
     */
    public function rem(string $id): string
    {
        try {
            $this->db->runDelete(Repository::TABLE, $id);
            return 'OK';
        } catch (\Exception $ex) {
            Core::echo(__METHOD__, $ex);
            return 'FAIL';
        }
    }

    /**
     * @cli get entry
     */
    public function get(string $id): array
    {
        $stmt = $this->db->runSelect(Repository::TABLE, ['id'], ['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * @cli list entries
     */
    public function list(): array
    {
        $stmt = $this->db->runSelect(Repository::TABLE, [], [], 'order by sortnum');
        return $stmt->fetchAll();
    }


    public function install(Context $ctx): array
    {
        $out = [];
        $out['tables'] = $this->db->migrate();
        $out['triggers'] = $this->db->triggerCreate([Repository::TABLE]);
        $out['triggertables'] = $this->db->triggerTableMigrate();
        return $out;
    }
}
