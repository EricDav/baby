<?php
    include 'connection.php';
    $mainData = sizeof($_POST) > 0 ? $_POST : (array)json_decode(file_get_contents("php://input"));
    $data = $mainData['results'];
    $d = file_get_contents(__DIR__ . '/data/fixtures.json');
    $fixtures = json_decode($d);
    if (sizeof($fixtures) == 0) {
        $result = array(
            'success' => false,
            'message' => 'Empty fixtures'
        );
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result); exit;
    }
    $pdo = DBConnection::getDB();
    if ($data) {
        if ($fixtures[0]->homeTeam == $data[0]->home && 
            $fixtures[0]->awayTeam == $data[0]->away && $mainData['weekNum'] != file_get_contents(__DIR__ . '/data/lastWeek.txt')) {
            try {
                for ($i = 0; $i < sizeof($fixtures); $i++) {
                    $resultData = (array)$data[$i];
                    $fix = $fixtures[$i];
                    $sql = 'INSERT INTO baby (league_num, week_num, home, away, home_odd, away_odd, draw_odd, home_draw_odd, away_draw_odd, any_body_odd, gg_odd, ng_odd, over_2, under_2, result, home_form, away_form, home_pos, away_pos, home_point, away_point, createAt) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
                    $stmt = $pdo->prepare($sql);
                    $param = [$mainData['leagueNum'], $mainData['weekNum'], $resultData['home'], $resultData['away'], $fix->home, $fix->away, $fix->draw, $fix->homeDraw, $fix->awayDraw, $fix->homeAway, $fix->GG, $fix->NG, $fix->over2, $fix->under2, $resultData['homeScore'] . '-' . $resultData['awayScore'], $resultData['homeForm'], $resultData['awayForm'], $resultData['homePos'], $resultData['awayPos'], $resultData['homePoint'], $resultData['awayPoint'],  gmdate('Y-m-d H:i:s')];
                    $stmt->execute($param);
                }
                $result = array(
                    'success' => true,
                    'message' => 'Data inserted successfully',
                    'fixtures_size' => sizeof($fixtures),
                    'data_size' => sizeof($data),
                    'fixtures' => $fixtures,
                    'data' => $data
                );
                file_put_contents(__DIR__ . '/data/fixtures.json', '[]');
                file_put_contents(__DIR__ . '/data/lastWeek.txt', $mainData['weekNum']);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($result); exit;
            } catch(Exception $e) {
                file_put_contents(__DIR__ . '/data/error.json', json_encode($e));
                file_put_contents(__DIR__ . '/data/fixtures.json', '[]');
                $result = array(
                    'success' => false,
                    'message' => $e->getMessage()
                );
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($result); exit;
            }
        } else {
            $result = array(
                'success' => true,
                'message' => 'Fixtures does not match probably waiting for the next result'
            );
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result); exit;
        }
    }

    $result = array(
        'success' => false,
        'message' => 'No payload or a it is a get request'
    );
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result); exit;
?>