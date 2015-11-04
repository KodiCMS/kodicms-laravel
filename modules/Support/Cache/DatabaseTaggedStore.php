<?php

namespace KodiCMS\Support\Cache;

use Illuminate\Database\ConnectionInterface;

class DatabaseTaggedStore extends SqLiteTaggedStore
{
    /**
     * Create a new database store.
     *
     * @param  \Illuminate\Database\ConnectionInterface $connection
     * @param  string                                   $table
     * @param  string                                   $prefix
     *
     * @return void
     */
    public function __construct(ConnectionInterface $connection, $table, $prefix = '')
    {
        $this->table = $table;
        $this->prefix = $prefix;
        $this->connection = $connection;
    }
}
