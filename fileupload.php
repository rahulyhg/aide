<?php

if ($_SERVER["SERVER_NAME"]=="aide-generaleye.rhcloud.com") {
    echo $OPENSHIFT_DATA_DIR;

    $env_var = getenv('OPENSHIFT_ENV_VAR');
    echo $env_var;
}

// Path to move uploaded files
$target_path = "uploads/";

// array for final json response
$response = array();

// getting server ip address
$server_ip = gethostbyname(gethostname());
echo $server_ip;
// final file url that is being uploaded
$file_upload_url = 'https://' . $_SERVER["SERVER_NAME"] . '/' . 'aide' . '/' . $target_path;
echo $file_upload_url;

if (isset($_FILES['image']['name'])) {
    $target_path = $target_path . basename($_FILES['image']['name']);

//    // reading other post parameters
//    $email = isset($_POST['email']) ? $_POST['email'] : '';
//    $website = isset($_POST['website']) ? $_POST['website'] : '';

    $response['file_name'] = basename($_FILES['image']['name']);
//    $response['email'] = $email;
//    $response['website'] = $website;

    try {
        // Throws exception incase file is not being moved
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            // make error flag true
            $response['error'] = true;
            $response['message'] = 'Could not move the file!';
        }

        // File successfully uploaded
        $response['message'] = 'File uploaded successfully!';
        $response['error'] = false;
        $response['file_path'] = $file_upload_url . basename($_FILES['image']['name']);
    } catch (Exception $e) {
        // Exception occurred. Make error flag true
        $response['error'] = true;
        $response['message'] = $e->getMessage();
    }
} else {
    // File parameter is missing
    $response['error'] = true;
    $response['message'] = 'Not received any file!';
}

// Echo final json response to client
echo json_encode($response);
?>
