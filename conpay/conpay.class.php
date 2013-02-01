<?php
class conpay
{
	function GetContent()
	{
		global $CONPAY_MERCHANT_ID, $CONPAY_API_KEY, $CONPAY_CONTAINER_ID, $CONPAY_MIN_PRICE;

		$user = null;
		$details = array();
		$user_details = array();
		$price = 0;

		$shop = new shop();
		$kernel =& singleton('kernel');
		$site_users =& singleton('user_access');
		$user_id = $kernel->GetCurrentUser();

		if (CURRENT_STRUCTURE_ID != 43)
		{
			$products[1] = array(
				($item_id = $GLOBALS['shop_item_path']['item']) => array(
					'shop_items_catalog_item_id' => $item_id,
					'shop_cart_item_quantity' => 1)
			);
		}
		else {
			$products = $shop->getCart();
		}

		foreach ($products[1] as $i => $item)
		{
			$product = $shop->getItem($item['shop_items_catalog_item_id']);
			$group = $shop->GetGroup($product['shop_groups_id']);

			$details[] = array(
				'name' => $product['shop_items_catalog_name'],
				'category' => $group['shop_groups_name'],
				'url' => ($host = 'http://'.$_SERVER['HTTP_HOST']).'/shop'.'/'.$group['shop_groups_path'].'/'.$product['shop_items_catalog_path'],
				'image' => $host.'/upload/shop_'.$product['shop_shops_id'].'/'.preg_replace('/(.)(?!.{0}$)/s', '$1/', sprintf("%03s", $i)).'/item_'.$i.'/'.$product['shop_items_catalog_image'],
				'quantity' => ($q = $item['shop_cart_item_quantity']), 'price' => ($p = $product['shop_items_catalog_price']),
			);

			$price += $q * $p;
		}

		if ($price < $CONPAY_MIN_PRICE) {
			return false;
		}

		if ($user_id)
		{
			$user = $site_users->GetUser($user_id);
			if ($v = $user['users_email']) {
				$user_details['email'] = $v;
			}
			if ($v = $user['users_name']) {
				$user_details['login'] = $v;
			}
			if ($v = $user['users_name_text']) {
				$user_details['user_name'] = $v;
			}
			if ($v = $user['users_surname']) {
				$user_details['user_surname'] = $v;
			}
			if ($v = $user['users_patronymic']) {
				$user_details['user_patronymic'] = $v;
			}
		}

		$api_key = $CONPAY_API_KEY;
		$merchant_id = $CONPAY_MERCHANT_ID;
		$checksum = md5($api_key.'!'.(string)$price.'!'.$merchant_id.(($user_details) ? '!'.implode($user_details, '!') : ''));

		$script = '<script type="text/javascript" src="http://www.conpay.ru/public/js/credits/btn.1.5.proxy.min.js"></script>';

		$script .= "
		<script type=\"text/javascript\">
		if (!jQuery) window.onload = mod_conpay;
		else jQuery(document).ready(mod_conpay);

		function mod_conpay() {
				window.conpay.init('/modules/conpay/conpay-proxy.php', {'className': 'button', "."'tagName': 'a', 'text': '<span>Купить в кредит</span>'}".(($user_details) ? ', '.json_encode($user_details) : '').");
				window.conpay.addButton('".$checksum."', '".(($cont_id = $CONPAY_CONTAINER_ID) ? $cont_id : 'conpay-link'.$id)."', ";

		$script .= json_encode($details);
		$script .= ");
		}
		</script>";

		echo $script;

		return true;
	}

	function Install() {
	}

	function UnInstall() {
	}
}

?>
