<?php

$in = $argv[1];
$object = new Verify2identitycard();
$result = $object->is2Identity($in);
//342224201809070111 true
//342224201809070112 false
echo $result ? '有效身份证号' : '无效身份证号';


class Verify2identitycard{

	function is2Identity($identity)
	{
		$vCity = array(
			'11','12','13','14','15','21','22',
			'23','31','32','33','34','35','36',
			'37','41','42','43','44','45','46',
			'50','51','52','53','54','61','62',
			'63','64','65','71','81','82','91'
		);
	 
		if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $identity)) return false;
	 
		if (!in_array(substr($identity, 0, 2), $vCity)) return false;
	 
		$identity = preg_replace('/[xX]$/i', 'a', $identity);
		$vLength = strlen($identity);
	 
		if ($vLength == 18)
		{
			$vBirthday = substr($identity, 6, 4) . '-' . substr($identity, 10, 2) . '-' . substr($identity, 12, 2);
		} else {
			$vBirthday = '19' . substr($identity, 6, 2) . '-' . substr($identity, 8, 2) . '-' . substr($identity, 10, 2);
		}
	 
		if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
		if ($vLength == 18)
		{
			$vSum = 0;
	 
			for ($i = 17 ; $i >= 0 ; $i--)
			{
				$vSubStr = substr($identity, 17 - $i, 1);
				$vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
			}
	 
			if($vSum % 11 != 1) return false;
		}
	 
		return true;
	}
}
