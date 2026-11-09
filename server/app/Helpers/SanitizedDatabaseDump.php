<?php

namespace App\Helpers;

use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LogicException;
use PDO;

class SanitizedDatabaseDump
{
    /**
     * @var list<string>
     */
    private const array SCHEMA_ONLY_TABLES = [
        'password_resets',
        'password_reset_tokens',
        'personal_access_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
    ];

    public function stream(): void
    {
        $connection = DB::connection()->getConfig();

        if (! in_array($connection['driver'], ['mysql', 'mariadb'], true)) {
            throw new LogicException('Database exports require MySQL or MariaDB.');
        }

        $endpoint = ! empty($connection['unix_socket'])
            ? "unix_socket={$connection['unix_socket']}"
            : "host={$connection['host']};port={$connection['port']}";

        $dumper = new Mysqldump(
            "mysql:{$endpoint};dbname={$connection['database']};charset=utf8mb4",
            (string) ($connection['username'] ?? ''),
            (string) ($connection['password'] ?? ''),
            [
                'default-character-set' => 'utf8mb4',
                'complete-insert' => true,
                'hex-blob' => true,
                'single-transaction' => true,
                'lock-tables' => false,
                'add-locks' => false,
                'skip-comments' => true,
                'skip-definer' => true,
                'routines' => true,
                'events' => true,
                'no-data' => array_map(
                    fn (string $table): string => ($connection['prefix'] ?? '').$table,
                    self::SCHEMA_ONLY_TABLES,
                ),
            ],
            array_replace($connection['options'] ?? [], [PDO::ATTR_PERSISTENT => false]),
        );

        $replacementPassword = Hash::make(bin2hex(random_bytes(32)));
        $userTables = array_map(
            fn (string $table): string => ($connection['prefix'] ?? '').$table,
            ['user', 'users'],
        );

        $dumper->setTransformTableRowHook(
            function (string $table, array $row) use ($replacementPassword, $userTables): array {
                if (in_array($table, $userTables, true)) {
                    if (array_key_exists('password', $row)) {
                        $row['password'] = $replacementPassword;
                    }

                    if (array_key_exists('remember_token', $row)) {
                        $row['remember_token'] = null;
                    }
                }

                return $row;
            },
        );

        $dumper->start('php://output');
    }
}
