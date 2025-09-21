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
        'complete_count' => 0,
        'abandon_count' => 0,
        'accept_count' => 0,
        'first_completed' => null,
        'last_completed' => null,
        'first_players' => [],
        'last_players' => [],
    ];

    $qb = $characters_db->createQueryBuilder();
    $qb->select(
        'COUNT(*) as accept_count',
        'SUM(CASE WHEN quest_complete_time IS NOT NULL THEN 1 ELSE 0 END) as complete_count',
        'SUM(CASE WHEN quest_abandon_time IS NOT NULL THEN 1 ELSE 0 END) as abandon_count',
        'MIN(quest_complete_time) as first_completed',
        'MAX(quest_complete_time) as last_completed'
    )
        ->from('quest_tracker')
        ->where('id = :id')
        ->setParameter('id', $id, ParameterType::INTEGER);
    $stmt = $characters_db->executeQuery($qb->getSQL(), $qb->getParameters());
    $stats = $stmt->fetchAssociative();
    if ($stats) {
        $output['accept_count'] = (int)$stats['accept_count'];
        $output['complete_count'] = (int)$stats['complete_count'];
        $output['abandon_count'] = (int)$stats['abandon_count'];
        $output['first_completed'] = $stats['first_completed'] ? date('Y-m-d H:i:s', strtotime($stats['first_completed'])) : null;
        $output['last_completed'] = $stats['last_completed'] ? date('Y-m-d H:i:s', strtotime($stats['last_completed'])) : null;
    }

    $qb = $characters_db->createQueryBuilder();
    $qb->select('characters.name as player_name', 'quest_tracker.quest_complete_time')
        ->from('quest_tracker')
        ->innerJoin('quest_tracker', 'characters', 'characters', 'quest_tracker.character_guid = characters.guid')
        ->where('quest_tracker.id = :id')
        ->andWhere('quest_tracker.quest_complete_time IS NOT NULL')
        ->setParameter('id', $id, ParameterType::INTEGER)
        ->orderBy('quest_tracker.quest_complete_time', 'ASC')
        ->setMaxResults(5);

    $stmt = $characters_db->executeQuery($qb->getSQL(), $qb->getParameters());
    $firstPlayers = $stmt->fetchAllAssociative();

    foreach ($firstPlayers as $player) {
        $output['first_players'][] = [
            'player_name' => $player['player_name'],
            'completed_at' => date('Y-m-d H:i:s', strtotime($player['quest_complete_time'])),
        ];
    }

    $qb = $characters_db->createQueryBuilder();
    $qb->select('characters.name as player_name', 'quest_tracker.quest_complete_time')
        ->from('quest_tracker')
        ->innerJoin('quest_tracker', 'characters', 'characters', 'quest_tracker.character_guid = characters.guid')
        ->where('quest_tracker.id = :id')
        ->andWhere('quest_tracker.quest_complete_time IS NOT NULL')
        ->setParameter('id', $id, ParameterType::INTEGER)
        ->orderBy('quest_tracker.quest_complete_time', 'DESC')
        ->setMaxResults(5);
    $stmt = $characters_db->executeQuery($qb->getSQL(), $qb->getParameters());
    $lastPlayers = $stmt->fetchAllAssociative();

    foreach ($lastPlayers as $player) {
        $output['last_players'][] = [
            'player_name' => $player['player_name'],
            'completed_at' => date('Y-m-d H:i:s', strtotime($player['quest_complete_time'])),
        ];
    }

    $Psr16Adapter->set($cache_key, $output, cache_time);
    echo json_encode($output);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error', 'details' => $e->getMessage()]);
    exit;
}
