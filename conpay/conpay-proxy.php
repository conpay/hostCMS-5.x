<?php
require_once './config/config.php';
// Подключаем скрипт с классом ConpayProxyModel, выполняющим бизнес-логику
require_once './ConpayProxyModel.php';

try
{
	// Создаем объект класса ConpayProxyModel
	$proxy = new ConpayProxyModel;
	// Устанавливаем свой идентификатор продавца
	$proxy->setMerchantId($CONPAY_MERCHANT_ID);
	// Устанавливаем кодировку, используемую на сайте (по-умолчанию 'UTF-8')
	$proxy->setCharset('UTF-8');
	// Выполняем запрос, выводя его результат
	echo $proxy->sendRequest();
}
catch (Exception $e) {
	echo json_encode(array('error'=>$e->getMessage()));
}
