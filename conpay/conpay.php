<?php
/**
 * Модуль conpay.
 *
 * Файл: /modules/conpay/conpay.php
 *
 * @author CONPAY.RU
 * @version 0.2
 */

/* Путь к модулю */
$module_path_name = 'conpay';

/* Имя модуля */
$module_name = 'Сервис CONPAY.RU';

/* Указание соответствия имени класса и модуля */
$GLOBALS['HOSTCMS_CLASS'][$module_path_name] = $module_path_name;
$kernel = &singleton('kernel');

/* Список файлов для загрузки */
$kernel->AddModuleFile($module_path_name, CMS_FOLDER . "modules/{$module_path_name}/{$module_path_name}.class.php");

/* Добавляем версию модуля */
$kernel->add_modules_version($module_path_name, '0.2', '29.01.2013');
