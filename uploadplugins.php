<?php
function uploadPlugins($uploadFolder='upload/')
{
    $result = check($_POST);
    $result2 = check($_FILES['file']);

    $isAjax = isset($result['ajax']) ? true : false;
    // header('Content-Length: ' . $result2['size']);

    echo '<pre>';
    var_dump($result);
    echo '</pre>';
    $result = check($_POST);
    $file = check($_FILES['file']);

    echo '<pre>';
    var_dump($file);
    echo '</pre>';
    // $user      = new AppUser($_SESSION['userConnected']);
    $upload = new AppUpload(0, $file, $result['filename'], $result['description'], 0);
    // $classroom = new AppPage($result['pageId']);

    // $user->setAll('has_upload_dir', 0);

    if (isset($result['app_template'])) {
        $_GET['template'] = $result['app_template'];
        echo 'app template : ' . $_GET['template'];
    }

    $upload->upload($uploadFolder);

    // alertAdmin('user submited project', $result['pageId']);
    if ($isAjax) {
        echo 'ajax';
    } else {
        // header('Location: ' . ADMINROOT . '/files/');
        // die();
    }
}
