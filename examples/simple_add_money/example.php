<?php
/**
 * Данный скрипт является упрощенной версий и не рекомендуется к использованию.
 */
$config = [
    'secret' => '',                     # Секретное слово
    'type' => 'GET',                    # Тип вебхука - GET или POST
    'database' => [                     # Настройки подклюсения к базе данных
        'driver' => 'mysql',            # Драйвер базы дынных. Поддерживается mysql и pgsql.
        'host' => 'localhost',          # Адрес базы данных
        'username' => 'root',           # Юзернейм (логин) базы данных
        'password' => '',               # Пароль базы данных
        'database' => 'my_project'      # Название базы данных
    ],
    'economy' => [                      # Настройка таблицы с балансами игроков
        'table-name' => 'money',        # Название таблицы с балансами игроков
        'nickname-column' => 'nickname',# Название колонки в таблице содержащей никнейм
        'balance-column' => 'balance',  # Название колонки в таблице содержащей баланс
        'bonus' => 1000                 # Сумма бонуса за голосование
    ],
    'advanced' => [                     # Дополнительные настройки
        'add-new-nickname' => false,    # Добавлять новый никнейм в таблицу если предоставленный не был найден
        'start-balance' => 0            # При включенном параметре addNewNickname какой баланс игркоа должен быть по-умолчанию
    ]
];

if ($config['type'] == 'GET') {
    $request = &$_GET;
} elseif ($config['type'] == 'POST') {
    $request = &$_POST;
} else {
    throw new Exception("Invalid configuration: type contains invalid value.", 500);
}

if (isset($request['nick']) && isset($request['hash'])) {
    if (md5(md5($request['nick'] . $config['secret'] . 'mcrate')) == $request['hash']) {
        $db = new PDO($config['database']['driver'] . ':host=' . $config['database']['host'] . ';dbname=' . $config['database']['database'], $config['database']['username'], $config['database']['password']);

        $select = $db->prepare("SELECT * FROM " . $config['economy']['table-name'] . " WHERE `" . $config['economy']['nickname-column'] . "`=?");
        $select->execute([$request['nick']]);
        if ($select->fetchColumn() > 0)
            $query = $db->prepare("UPDATE " . $config['economy']['table-name'] . " SET `" . $config['economy']['balance-column'] . "`=`" . $config['economy']['balance-column'] . "`+" . $config['economy']['bonus'] . " WHERE `" . $config['economy']['nickname-column'] . "`=?");
        elseif ($config['advanced']['addNewNickname'])
            $query = $db->prepare("INSERT INTO " . $config['economy']['table-name'] . " (`" . $config['economy']['nickname-column'] . "`,`" . $config['economy']['balance-column'] . "`) VALUES (?, " . (intval($config['advanced']['start-balance']) + intval($config['economy']['bonus'])) . ")");
        $query->execute([$_GET['nick']]);

        if ($db->errorCode() != 0000) {
            $error_array = $db->errorInfo();
            throw new Exception("Error in SQL: " . $error_array[2]);
        }
    } else {
        throw new InvalidArgumentException("Invalid hash. Check secret.", 400);
    }
} else {
    throw new InvalidArgumentException("Required data not provided.", 400);
}
