<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "flood_relief_management_system");
if ($conn->connect_error) {
    echo json_encode(['status'=>'error','message'=>'Database connection failed']);
    exit;
}

$requestID = intval($_POST['requestID'] ?? 0); 
$userID = intval($_POST['userID'] ?? 0);
$userName = trim($_POST['userName'] ?? '');
$contactPerson = trim($_POST['contactPerson'] ?? '');
$contactNumber1 = trim($_POST['contactNumber1'] ?? '');
$contactNumber2 = trim($_POST['contactNumber2'] ?? '');
$address = trim($_POST['address'] ?? '');
$familyMembers = intval($_POST['familyMembers'] ?? 0);
$reliefType = trim($_POST['reliefType'] ?? '');
$district = trim($_POST['district'] ?? '');
$divSec = trim($_POST['divSec'] ?? '');
$gnDivision = trim($_POST['gnDivision'] ?? '');
$floodLevel = trim($_POST['floodLevel'] ?? '');
$description = trim($_POST['description'] ?? '');

if (!$userID || !$userName || !$contactPerson || !$address || !$familyMembers || !$reliefType || !$district || !$divSec || !$gnDivision || !$floodLevel) {
    echo json_encode(['status'=>'error','message'=>'Please fill all required fields']);
    exit;
}

if (!$contactNumber1 && !$contactNumber2) {
    echo json_encode(['status'=>'error','message'=>'Please provide at least one contact number']);
    exit;
}

$conn->begin_transaction();

try {
    if ($requestID > 0) {
        $stmt = $conn->prepare("UPDATE relief_request SET 
            AffectedUserID=?, ReliefType=?, District=?, DivisionalSecretariat=?, GNDivision=?, Address=?, NoOfFamilyMembers=?, ContactName=?, SeverityLevel=?, Description=? 
            WHERE RequestID=?");
        $stmt->bind_param(
            "issssssissi",
            $userID,
            $reliefType,
            $district,
            $divSec,
            $gnDivision,
            $address,
            $familyMembers,
            $contactPerson,
            $floodLevel,
            $description,
            $requestID
        );
        $stmt->execute();
        $stmt->close();

        $stmtDel = $conn->prepare("DELETE FROM request_contact WHERE RequestID=?");
        $stmtDel->bind_param("i", $requestID);
        $stmtDel->execute();
        $stmtDel->close();

    } else {
        $stmt = $conn->prepare("INSERT INTO relief_request 
            (AffectedUserID, AdminUserID, ReliefType, District, DivisionalSecretariat, GNDivision, Address, NoOfFamilyMembers, ContactName, SeverityLevel, Description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $adminID = null; 
        $stmt->bind_param(
            "iisssssisss",
            $userID,
            $adminID,
            $reliefType,
            $district,
            $divSec,
            $gnDivision,
            $address,
            $familyMembers,
            $contactPerson,
            $floodLevel,
            $description
        );
        $stmt->execute();
        $requestID = $stmt->insert_id;
        $stmt->close();
    }

    $numbers = array_filter([$contactNumber1, $contactNumber2]);
    $stmt2 = $conn->prepare("INSERT INTO request_contact (RequestID, ContactNumber) VALUES (?, ?)");
    foreach ($numbers as $num) {
        $stmt2->bind_param("is", $requestID, $num);
        $stmt2->execute();
    }
    $stmt2->close();

    $conn->commit();
    echo json_encode(['status'=>'success']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}

$conn->close();
