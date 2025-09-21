<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\ParameterType;
use Phpfastcache\Helper\Psr16Adapter;

$defaultDriver = 'Files';
$Psr16Adapter = new Psr16Adapter($defaultDriver);

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

if (empty($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid quest ID']);
    exit;
}

$id = (int)$_GET['id'];

if ($id < 1 || $id > 100000) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid quest ID']);
    exit;
}

try {
    $world_db = DriverManager::getConnection([
        'dbname'   => $mysql_world,
        'user'     => $mysql_username,
        'password' => $mysql_password,
        'host'     => $mysql_host,
        'driver'   => 'pdo_mysql',
        'charset'  => 'utf8mb4',
    ]);

    $characters_db = DriverManager::getConnection([
        'dbname'   => $mysql_characters,
        'user'     => $mysql_username,
        'password' => $mysql_password,
        'host'     => $mysql_host,
        'driver'   => 'pdo_mysql',
        'charset'  => 'utf8mb4',
    ]);

    $cache_key = 'quest_data_' . md5($id);
    $cachedData = $Psr16Adapter->get($cache_key);
    if ($cachedData) {
        echo json_encode($cachedData);
        exit;
    }

    $qb = $world_db->createQueryBuilder();
    $qb->select('q.ID as quest_id', 'q.LogTitle as quest_title')
        ->from('quest_template', 'q')
        ->where('q.ID = :id')
        ->setParameter('id', $id, ParameterType::INTEGER);
    $stmt = $world_db->executeQuery($qb->getSQL(), $qb->getParameters());
    $quest = $stmt->fetchAssociative();

    if (!$quest) {
        http_response_code(404);
        echo json_encode(['error' => 'Quest not found']);
        exit;
    }

    $output = [
        'quest_id' => $quest['quest_id'],
        'quest_title' => $quest['quest_title'],
    ];

    // $Psr16Adapter->set($cache_key, $output, cache_time);
    echo json_encode($output);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error', 'details' => $e->getMessage()]);
    exit;
}
