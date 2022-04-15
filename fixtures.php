<?php
    $data = $_POST['fixtures'];
    $prevData = file_get_contents(__DIR__ . '/data/fixtures.json');
    $milli = round(microtime(true) * 1000);
    $minutes = round($milli/60000);
    $lastSavedFixtures = file_get_contents(__DIR__ . '/data/fixturesTimestamp.txt');
    $lastMin = round((int)$lastSavedFixtures/60000);
    $diff = $minutes - $lastMin;
    if (sizeof(json_decode($prevData)) == 0 || $diff > 6) {
        file_put_contents(__DIR__ . '/data/fixtures.json', json_encode($data));
        $milli = round(microtime(true) * 1000);
        file_put_contents(__DIR__ . '/data/fixturesTimestamp.txt', $milli);
        $result = array(
            'inserted' => true,
            'success' => true,
            'message' => 'Fixtures has been saved'
        );
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result); exit;
    }

    $result = array(
        'inserted' => false,
        'success' => true,
        'message' => 'Data already exist, waiting for results'
    );

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result); exit;
?>
