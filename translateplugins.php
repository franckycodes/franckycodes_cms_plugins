<?php
use franckycodes\database\LightDb;

//translate plugins by franckycodes
function translatePlugins()
{
    $db=new LightDb();
    //translations
    $translations = createTable('to_translate', ['id int auto_increment primary key',
        'maintext text',
        'page_url varchar(255)',
        'date_added datetime',
        'translation_language VARCHAR(255)',
        'text_page int']);
    try {
        $db->query($translations);
    } catch (PDOException $e) {

    }
    $query = createTable('all_translations', ['id int auto_increment primary key',
        'text_id int',
        'main_translation text',
        'language_type VARCHAR(255)',
        'date_added datetime',
        'date_remove datetime']);
    try
    {
        $db->query($query);
    } catch (PDOException $e) {

    }
}

//translate
function translate($str)
{

    echo getTranslate($str);
}

//test language here
function getTranslate($str)
{
    $db = new LightDb();
    $table = 'to_translate';
    $check = $db->query('SELECT * FROM ' . $table . ' WHERE maintext=:qText', true, ['qText' => htmlspecialchars($str)], true, true);

    if (gettype($check) == 'boolean') {
        $db->query('INSERT INTO ' . $table . '(maintext, date_added) VALUES(:qText, NOW())',
            true,
            ['qText' => htmlspecialchars($str)]);
    }

    //checking language
    $language = htmlspecialchars(isset($_SESSION['user_language']) ? $_SESSION['user_language'] : 'fr');

    if (gettype($check) != 'boolean') {
        $result = $db->query('SELECT * FROM all_translations WHERE text_id=:qId AND language_type=:qLang ORDER BY id
	    DESC', true,
            ['qId' => $check['id'],
                'qLang' => $language], true, true);

        return gettype($result) != 'boolean' ? $result['main_translation'] : $str;
    }
    return $str;
}
