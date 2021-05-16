<?php
function with_trailing_slash($path) {
    return rtrim($path, '/') . '/';
}

if ($_GET['healthcheck'] == 'true') {
    header('OBS-System-Health: true');
    http_response_code(204);
    exit();
}

$HTTP_USER_AGENT = $_SERVER["HTTP_USER_AGENT"];
$AUTH = $_SERVER["HTTP_AUTHORIZATION"];
$OBS_UPLOAD_DIR = with_trailing_slash(
    getenv("OBS_UPLOAD_DIR") ?: "./obs-uploads/"
);

if (strpos($HTTP_USER_AGENT, "OBS") === false || strpos($AUTH, "OBSUserId") === false) {
    http_response_code(401);
    exit();
}
$user = rawurlencode(str_replace("OBSUserId ", "", $AUTH));
$target_dir =  $OBS_UPLOAD_DIR.'/'.$user.'/';
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}
$target_file = $target_dir . basename($_FILES["body"]["name"]);
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


// Check file size
if ($_FILES["body"]["size"] > 50 * 1024 * 1024) {
    http_response_code(413);
    exit();
}

// Allow certain file formats
if ($fileType != "csv") {
    http_response_code(415);
    exit();
}


if (move_uploaded_file($_FILES["body"]["tmp_name"], $target_file)) {
    http_response_code(201);
    exit();
} else {
    http_response_code(500);
    var_dump($_FILES["body"]["error"]);
    exit();
}
