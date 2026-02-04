<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "flood_relief_management_system");
if ($conn->connect_error) {
    echo json_encode(["error" => "DB Connection failed: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    $requestID = isset($input['requestID']) ? intval($input['requestID']) : 0;

    if ($requestID <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid Request ID"]);
        exit;
    }

    $conn->begin_transaction();

    try {
        $stmt1 = $conn->prepare("DELETE FROM request_contact WHERE RequestID = ?");
        $stmt1->bind_param("i", $requestID);
        $stmt1->execute();
        $stmt1->close();

        $stmt2 = $conn->prepare("DELETE FROM relief_request WHERE RequestID = ?");
        $stmt2->bind_param("i", $requestID);
        $stmt2->execute();

        if ($stmt2->affected_rows > 0) {
            $conn->commit();
            echo json_encode(["success" => true]);
        } else {
            $conn->rollback();
            echo json_encode(["success" => false, "message" => "Request not found"]);
        }

        $stmt2->close();
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }

    $conn->close();
    exit;
}

$userID = isset($_GET['userID']) ? intval($_GET['userID']) : 0;
if ($userID <= 0) {
    echo json_encode(["error" => "Invalid UserID"]);
    exit;
}

$sql = "
SELECT 
    rr.AffectedUserID AS user_id,
    rr.RequestID AS request_id,
    rr.ReliefType AS relief_type,
    rr.ContactName AS contact_name,
    rr.SeverityLevel AS severity_level,
    rr.NoOfFamilyMembers AS family_members,
    rr.Address AS address,
    rr.Description AS description,
    GROUP_CONCAT(rc.ContactNumber SEPARATOR ', ') AS contact_number
FROM relief_request rr
LEFT JOIN request_contact rc ON rr.RequestID = rc.RequestID
WHERE rr.AffectedUserID = ?
GROUP BY rr.RequestID
ORDER BY rr.RequestID DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();

