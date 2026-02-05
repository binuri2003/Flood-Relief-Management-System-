<?php
include 'database.php'; 

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "GET") {
    if (isset($_GET['action']) && $_GET['action'] == 'summary') {
        $selectedArea = $_GET['area'];
        $selectedRelief = $_GET['reliefType'];

        $userQuery = "SELECT COUNT(*) as total FROM user";
        $severityQuery = "SELECT COUNT(*) as highCount FROM relief_request WHERE SeverityLevel = 'High'";
        $reliefTypeQuery = "SELECT COUNT(*) as typeCount FROM relief_request WHERE ReliefType = '$selectedRelief'";

        if ($selectedArea != "All") {
            $severityQuery .= " AND District = '$selectedArea'";
            $reliefTypeQuery .= " AND District = '$selectedArea'";
        }

        $userResult = mysqli_query($conn, $userQuery);
        $severityResult = mysqli_query($conn, $severityQuery);
        $typeResult = mysqli_query($conn, $reliefTypeQuery);

        $summaryData = [
            "totalUsers" => mysqli_fetch_assoc($userResult)['total'],
            "highSeverity" => mysqli_fetch_assoc($severityResult)['highCount'],
            "reliefCount" => mysqli_fetch_assoc($typeResult)['typeCount'],
            "typeName" => $selectedRelief
        ];
        echo json_encode($summaryData);
    } 
    else if (isset($_GET['userId'])) {
        $id = $_GET['userId'];
        $result = mysqli_query($conn, "SELECT * FROM user WHERE UserID = $id");
        echo json_encode(mysqli_fetch_assoc($result));
    }
    else {
        $result = mysqli_query($conn, "SELECT UserID, FirstName, LastName, UserName, Role FROM user");
        $userList = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $userList[] = $row;
        }
        echo json_encode($userList);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {

    if (isset($_GET['deleteId'])) {

        $userIdToDelete = intval($_GET['deleteId']);
        mysqli_query($conn,
            "DELETE FROM request_contact 
             WHERE RequestID IN (
                SELECT RequestID FROM relief_request 
                WHERE AffectedUserID = $userIdToDelete 
                   OR AdminUserID = $userIdToDelete
             )"
        );

        mysqli_query($conn,
            "DELETE FROM relief_request 
             WHERE AffectedUserID = $userIdToDelete 
                OR AdminUserID = $userIdToDelete"
        );

        
        mysqli_query($conn,
            "DELETE FROM user_contact 
             WHERE UserID = $userIdToDelete"
        );

        
        mysqli_query($conn,
            "DELETE FROM affected_person 
             WHERE UserID = $userIdToDelete"
        );

        mysqli_query($conn,
            "DELETE FROM admin 
             WHERE UserID = $userIdToDelete"
        );
        if (mysqli_query($conn,
            "DELETE FROM user WHERE UserID = $userIdToDelete"
        )) {
            http_response_code(200);
            echo "User deleted successfully";
        } else {
            http_response_code(500);
            echo "Database Error";
        }
    }
    exit;
}

mysqli_close($conn);
?>