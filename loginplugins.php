<?php
use franckycodes\database\LightDb;
function loginPluginModule()
{
    //multi users system
    $db = new LightDb();
    $users = createTable('app_users',
        ['id int auto_increment primary key',
            'user_name VARCHAR(255)',
            'user_email VARCHAR(255)',
            'user_password VARCHAR(255)',
            'date_created DATETIME']);

    try {
        $db->query($users);
    } catch (PDOException $e) {
    }
    alterTable('app_users', 'user_lang VARCHAR(25)');
    alterTable('app_users', 'user_address VARCHAR(255)');
    alterTable('app_users', 'user_initial VARCHAR(50), user_matricule INT');

}
function loginPlugin($pages, $actionLogged = 'somefunc')
{
    loginPluginModule();
    $db = new LightDb();
    $mainPage = isset($pages[URL_COUNTER]) ? $pages[URL_COUNTER] : 'login';

    if (isset($_SESSION['newsession'])) {

        if ($mainPage == 'logout') {
            unset($_SESSION['newsession']);
            header('Location: ' . WEBROOT . 'home/');
            die();
        }
        // echo 'hey';
        $actionLogged($pages);
    } else {
        // echo 'you have to login';
        switch ($mainPage) {

            case 'subscribe':
                $error = 0;
                if (!isset($_POST['initiale'], $_POST['matricule'], $_POST['login'], $_POST['password'])) {
                    $error = 'all';
                }
                $query = insertQuery('app_users');
                // echo $query;
                // try{

                if (isset($_POST['initiale'], $_POST['matricule'], $_POST['login'], $_POST['password'])) {
                    $result = check($_POST);

                    $check = $db->query('SELECT * FROM app_users WHERE (user_initial=:qInitial AND user_matricule=:qMatricule) || user_email=:qEmail', true,
                        ['qInitial' => $result['initiale'],
                            'qMatricule' => $result['matricule'],
                            'qEmail' => $result['login']], true, true);

                    //password_verify(password, hash)
                    if (gettype($check) != 'boolean') {
                        // echo 'user exists';
                        $error = 'user exists';
                        if (isset($result['ajax'])) {
                            // echo 'user exists';
                        } else {
                            // header('Location: '.WEBROOT.'subscribe/userexists/');
                            // die();
                        }
                    } else {
                        $db->query('INSERT INTO app_users(user_name, user_email,user_password,
			    			user_initial,user_matricule, date_created) VALUES(LOWER(:pusername), LOWER(:puser_email), :puser_password, LOWER(:puser_initial), :puser_matricule, NOW())', true, [
                            'pusername' => strtolower($result['initiale'] . '-' . $result['matricule']),
                            'puser_email' => strtolower($result['login']),
                            'puser_password' => password_hash($result['password'], PASSWORD_DEFAULT),
                            'puser_initial' => strtolower($result['initiale']),
                            'puser_matricule' => (int) $result['matricule']]);
                        echo 'new user created';
                        if (isset($result['ajax'])) {
                            echo 'success';
                        } else {
                            header('Location: ' . WEBROOT . 'login/accountcreated');
                            die();
                        }
                    }

                    // echo $query;
                    // echo '<pre>';
                    // var_dump($result);
                    // echo '</pre>';
                    //die();
                }
                // }catch(PDOException $e)
                // {
                //     echo $e->getMessage();
                // }
                $title = 'Inscription';
                $core = 'codes/plugins/login/subscribe.php';

                include MAIN_TEMPLATE;

                break;

            case 'home':
            default:
                $error = 0;
                if (!isset($_POST['login'], $_POST['password'])) {
                    $error = 'all';
                }
                if (isset($_POST['login'], $_POST['password'])) {
                    $result = check($_POST);
                    $users = $db->query('SELECT * FROM app_users', false, [], true);
                    // echo insertQuery('app_users');

                    $check = $db->query('SELECT * FROM app_users WHERE (LOWER(user_email)=LOWER(:qLogin) || LOWER(user_initial)=LOWER(:qLogin) || user_matricule=:qLogin) ', true,
                        ['qLogin' => $result['login']], true, true);

                    if (gettype($check) != 'boolean' && password_verify($result['password'], $check['user_password'])) {
                        $_SESSION['newsession'] = (int) $check['id'];
                        // echo 'welcome';
                        try {
                            $user = new AppUser($check['id']);
                            $user->setAll('user_online', 1);
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                        header('Location: ' . WEBROOT . 'profile/');
                        die();
                    } else {
                        $error = 'user exists';
                        // echo 'user doesnt exists';
                    }
                    // echo '<pre>';
                    // var_dump($result);
                    // echo '</pre>';
                    // die();
                } else {
                }
                // header('Location: '.WEBROOT.'login/');
                // die();
                $title = 'Login';
                $core = 'codes/plugins/login/login.php';

                include MAIN_TEMPLATE;
                break;
        }
    }
}
