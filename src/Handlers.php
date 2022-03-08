<?php

namespace Bpartner\Jsonrpc;

class Handlers
{
    private static array $handlers;

    public static function setHandler(string $alias, string $handler): void
    {
        self::$handlers[$alias] = $handler;
    }

    public static function resolveClassname(string $alias): string
    {
        return self::$handlers[$alias] ?? '';
    }
}
