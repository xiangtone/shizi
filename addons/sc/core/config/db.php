<?php
defined('IN_IA') or define('IN_IA', true);
require __DIR__ . '/../../../../data/config.php';

if (!isset($config['db']['master']))
    $config['db']['master'] = [];

if (empty($config['db']['master']['host']))
    $config['db']['master']['host'] = $config['db']['host'];

if (empty($config['db']['master']['port']))
    $config['db']['master']['port'] = $config['db']['port'];

if (empty($config['db']['master']['database']))
    $config['db']['master']['database'] = $config['db']['database'];

if (empty($config['db']['master']['username']))
    $config['db']['master']['username'] = $config['db']['username'];

if (empty($config['db']['master']['password']))
    $config['db']['master']['password'] = $config['db']['password'];

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . $config['db']['master']['host'] . ';port=' . $config['db']['master']['port'] . ';dbname=' . $config['db']['master']['database'],
    'username' => $config['db']['master']['username'],
    'password' => $config['db']['master']['password'],
    'charset' => 'utf8',
    'tablePrefix' => 'sc_',
];
