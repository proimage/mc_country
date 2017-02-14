<?php

if (!defined('MC_AUTHOR')) {
    define('MC_AUTHOR', 'Michael Cohen');
    define('MC_DOCS_URL', 'https://github.com/proimage/mc_country');
    define('MC_AUTHOR_URL', 'https://github.com/proimage/mc_country');
    define('MC_DESC', 'Detect user\'s country using IP2Nation module.');
    define('MC_NAME', 'MC Country');
    define('MC_EXT_NAME', 'mc_country');
    define('MC_NAMESPACE', 'MCCountry\MCCountry');
    define('MC_SETTINGS_EXIST', false);
    define('MC_VER', '1.1.2');
}

return array(
    'author' => MC_AUTHOR,
    'author_url' => MC_AUTHOR_URL,
    'name' => MC_NAME,
    'description' => MC_DESC,
    'version' => MC_VER,
    'namespace' => MC_NAMESPACE,
    'docs_url' => MC_DOCS_URL,
    'settings_exist' => MC_SETTINGS_EXIST
);
