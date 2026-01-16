<?php

//declare(strict_types=1);

namespace cryodrift\quicklinks\db;


use cryodrift\fw\Context;
use cryodrift\fw\trait\DbHelper;
use cryodrift\fw\trait\DbHelperMigrate;
use cryodrift\fw\trait\DbHelperTrigger;

class Repository
{
    use DbHelper;
    use DbHelperMigrate;
    use DbHelperTrigger;

    const string COLUMNS = 'id,url,name,sortnum,deleted,changed,created';
    public array $datafiles = [];
    const string TABLE = 'quicklinks';

    public function __construct(protected Context $ctx, string $storagedir)
    {
        $connectionstring = $storagedir . $ctx->user() . '/';
        $this->datafiles[] = $connectionstring . "quicklinks.sqlite";
        $this->connect('sqlite:' . $connectionstring . 'quicklinks.sqlite');
    }


}
