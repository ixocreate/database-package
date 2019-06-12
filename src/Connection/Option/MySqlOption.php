<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Connection\Option;

final class MySqlOption implements ConnectionOptionInterface
{
    /**
     * @var string|null
     */
    private $user = null;

    /**
     * @var string|null
     */
    private $password = null;

    /**
     * @var string|null
     */
    private $host = null;

    /**
     * @var int|null
     */
    private $port = null;

    /**
     * @var string|null
     */
    private $dbname = null;

    /**
     * @var string|null
     */
    private $unixSocket = null;

    /**
     * @var string|null
     */
    private $charset = null;

    /**
     * MySqlOption constructor.
     * @param string|null $user
     * @param string|null $password
     * @param string|null $host
     * @param int|null $port
     * @param string|null $dbname
     * @param string|null $unixSocket
     * @param string|null $charset
     */
    public function __construct(string $dbname , string $user = 'root', string $password = '', ?string $host = null, ?int $port = null,  ?string $unixSocket = null, string $charset = 'utf8mb4')
    {
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->port = $port;
        $this->unixSocket = $unixSocket;
        $this->charset = $charset;
    }


    /**
     * @param string $user
     * @return MySqlOption
     */
    public function withUser(string $user): MySqlOption
    {
        $option = clone $this;
        $option->user = $user;

        return $option;
    }

    /**
     * @param string $password
     * @return MySqlOption
     */
    public function withPassword(string $password): MySqlOption
    {
        $option = clone $this;
        $option->password = $password;

        return $option;
    }

    /**
     * @param string $host
     * @return MySqlOption
     */
    public function withHost(string $host): MySqlOption
    {
        $option = clone $this;
        $option->host = $host;

        return $option;
    }

    /**
     * @param int $port
     * @return MySqlOption
     */
    public function withPort(int $port): MySqlOption
    {
        $option = clone $this;
        $option->port = $port;

        return $option;
    }

    /**
     * @param string $dbName
     * @return MySqlOption
     */
    public function withDbName(string $dbName): MySqlOption
    {
        $option = clone $this;
        $option->dbname = $dbName;

        return $option;
    }

    /**
     * @param string $unixSocket
     * @return MySqlOption
     */
    public function withUnixSocket(string $unixSocket): MySqlOption
    {
        $option = clone $this;
        $option->unixSocket = $unixSocket;

        return $option;
    }

    /**
     * @param string $charset
     * @return MySqlOption
     */
    public function withCharset(string $charset): MySqlOption
    {
        $option = clone $this;
        $option->charset = $charset;

        return $option;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $config = [
            'driver' => 'pdo_mysql',
        ];

        foreach (['user', 'password', 'dbname', 'host', 'port', 'charset'] as $item) {
            if ($this->{$item} !== null) {
                $config[$item] = $this->{$item};
            }
        }

        if ($this->unixSocket !== null) {
            $config['unix_socket'] = $this->unixSocket;
        }

        return $config;
    }
}
