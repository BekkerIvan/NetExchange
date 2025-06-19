<?php
class RedisStore {
    private Redis $RedisObj;

    public function __construct() {
        if (!class_exists('Redis')) {
            throw new Exception('Redis extension not installed');
        }

        if (!defined('REDIS_HOST') || !defined('REDIS_PORT')) {
            throw new Exception('Redis host and port not defined');
        }

        $this->RedisObj = new Redis();
        $this->RedisObj->connect(REDIS_HOST, REDIS_PORT);

        if (defined('REDIS_PASSWORD') && strlen(REDIS_PASSWORD)) {
            $this->RedisObj->auth(REDIS_PASSWORD);
        }
    }

    public function set($key, $value): bool|Redis {
        return $this->RedisObj->set($key, $value);
    }

    public function get($key) {
        return $this->RedisObj->get($key);
    }

    public function delete($key): bool|int|Redis {
        return $this->RedisObj->del($key);
    }

    public function __destruct() {
        $this->RedisObj->close();
    }

    /**
     * @param string      $KeyStr
     * @param int         $ExpirationInSecondsInt
     * @param string|null $TTL command supports a set of options:
     *
     * NX -- Set expiry only when the key has no expiry
     * XX -- Set expiry only when the key has an existing expiry
     * GT -- Set expiry only when the new expiry is greater than current one
     * LT -- Set expiry only when the new expiry is less than current one
     *
     * @return bool|Redis
     * @throws RedisException
     */
    public function setExpire(string $KeyStr, int $ExpirationInSecondsInt = -1, ?string $TTL = null): bool|Redis {
        return $this->RedisObj->expireAt($KeyStr, time()+$ExpirationInSecondsInt, $TTL);
    }

    public function exists($key): bool|int {
        return $this->RedisObj->exists($key);
    }

    public function hash_set($Key, $hash, $value): bool {
        return (bool) $this->RedisObj->hSet($Key, $hash, $value);
    }

    public function hash_set_all(string $Key, array $KeyValuePairArr): bool {
        $SuccessBool = true;
        foreach ($KeyValuePairArr as $Hash => $Value) {
            $SuccessBool &= $this->RedisObj->hSet($Key, $Hash, $Value);
        }
        return $SuccessBool;
    }

    public function hash_get($Key, $hash): string|bool {
        return $this->RedisObj->hGet($Key, $hash);
    }

    public function hash_get_all($Key): array {
        return $this->RedisObj->hGetAll($Key);
    }

    public function hash_length($Key): int  {
        return $this->RedisObj->hLen($Key);
    }

    public function hash_delete($Key, $hash): bool {
        return (bool) $this->RedisObj->hDel($Key, $hash);
    }

    public function hash_delete_all($HashKeyStr): bool {
        $HasBeenDeletedBool = true;
        foreach (array_keys($this->RedisObj->hGetAll($HashKeyStr)) as $HashFieldKeyStr) {
            $HasBeenDeletedBool &= $this->RedisObj->hDel($HashKeyStr, $HashFieldKeyStr);
        }
        return $HasBeenDeletedBool;
    }

    public function hash_exists($Key, $hash): bool {
        return (bool) $this->RedisObj->hExists($Key, $hash);
    }

}
