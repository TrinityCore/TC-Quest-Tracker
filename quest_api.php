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

if (empty($_GET['page']) || !is_numeric($_GET['page']) || $_GET['page'] < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid page number']);
    exit;
}

$page = (int)$_GET['page'];
$pageSize = !empty($_GET['pageSize']) && is_numeric($_GET['pageSize']) ? (int)$_GET['pageSize'] : 10;
$query = !empty($_GET['query']) ? $_GET['query'] : '';

if ($pageSize < 1 || $pageSize > 100) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid page size']);
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

    $cache_key = 'quest_search_' . md5($query . '_' . $page . '_' . $pageSize);
    $cachedData = $Psr16Adapter->get($cache_key);
    if ($cachedData) {
        echo json_encode($cachedData);
        exit;
    }

    $qb = $world_db->createQueryBuilder();
    $qb->select('q.ID as quest_id', 'q.LogTitle as quest_title')
        ->from('quest_template', 'q');
    if ($query !== '') {
        $qb->where($qb->expr()->or(
            $qb->expr()->like('q.LogTitle', ':search'),
            // $qb->expr()->like('q.LogDescription', ':search'),
            // $qb->expr()->like('q.QuestDescription', ':search'),
            // $qb->expr()->like('q.QuestCompletionLog', ':search')
        ))
            ->setParameter('search', '%' . $query . '%', ParameterType::STRING);
    }

    $countQb = clone $qb;
    $countQb->select('COUNT(*) as cnt');
    $countStmt = $world_db->executeQuery($countQb->getSQL(), $countQb->getParameters());
    $total = (int)$countStmt->fetchOne();
    $qb->setFirstResult(($page - 1) * $pageSize)
        ->setMaxResults($pageSize);

    $stmt = $world_db->executeQuery($qb->getSQL(), $qb->getParameters());
    $quests = $stmt->fetchAllAssociative();

    $output = [
        'total' => $total,
        'page' => $page,
        'quests' => $quests
    ];

    $Psr16Adapter->set($cache_key, $output, cache_time);
    echo json_encode($output);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error', 'details' => $e->getMessage()]);
    exit;
}
