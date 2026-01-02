
<?php
include 'db_config.php';
header('Content-Type: application/json');

// Read POST JSON
$data = json_decode(file_get_contents('php://input'), true);

if(!isset($data['id']) || !isset($data['status'])){
    echo json_encode(['success'=>false,'message'=>'Missing parameters']);
    exit;
}

try {
    $pdo = getPDOConnection();

    $stmt = $pdo->prepare("UPDATE waiting_list SET status = :status WHERE waiting_id = :id");
    $stmt->execute([
        ':status' => $data['status'],
        ':id' => $data['id']
    ]);

    echo json_encode(['success'=>true]);
} catch(Exception $e){
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
?>


























 
