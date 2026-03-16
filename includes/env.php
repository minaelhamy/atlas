<?php

if (!function_exists('atlas_load_env_file')) {
    /**
     * Load KEY=VALUE pairs from a local env file into process env, $_ENV, and $_SERVER.
     */
    function atlas_load_env_file($path)
    {
        if (!is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }

            if (strpos($line, '=') === false) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if ($name === '') {
                continue;
            }

            $length = strlen($value);
            if ($length >= 2) {
                $first = $value[0];
                $last = $value[$length - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            putenv($name . '=' . $value);
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

if (!function_exists('atlas_env')) {
    /**
     * Read an environment variable with a fallback.
     */
    function atlas_env($key, $default = null)
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false || $value === null || $value === '') {
            return $default;
        }

        return $value;
    }
}

$atlasRoot = dirname(__DIR__);
atlas_load_env_file($atlasRoot . '/.env');
atlas_load_env_file($atlasRoot . '/.env.local');
