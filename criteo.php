<?php
/**
 *
 *
 * @author Dmitriy Kaledin
 * @package Lucy In The Sky
 */

/*
Example: 

$criteo = new Criteo();

$criteo->setAccount(array('account' => 31037));
$criteo->setEmail(array('email' => ''));
$criteo->setSiteType(array('type' => 'd'));
$criteo->trackTransaction(array(
	'id' => 1567893,
	'item' => array(
		array('id' => 45667, 'price' => 34.50, 'quantity' => 1),
		array('id' => 46233, 'price' => 27.90, 'quantity' => 1),
	)
));

echo $criteo->getCode();
*/

class Criteo {

	private $params = array();

	private $allow_methods = array(
		'setAccount',
		'setEmail',
		'setSiteType',
		'trackTransaction',
		'viewHome',
		'viewList',
		'viewItem',
		'viewBasket');

	/**
	 *
	 *
	 * @param String  $method
	 * @param Array   $args
	 * @return Method
	 */
	public function __call($method, $args) {
		if (in_array($method, $this->allow_methods)) {
			return $this->add_param($method, $args);
		}
	}


	/**
	 *
	 *
	 * @param String  $event
	 * @param Array   $array
	 */
	private function add_param($event, $array) {
		$param = array(
			'event' => $event
		);

		// var_dump($array);

		if (count($array) > 0) {
			foreach ($array[0] as $key => $value) {
				$param[$key] = $value;
			}
		}

		$this->params[] = json_encode($param);
	}


	/**
	 *
	 *
	 * @return String JS code
	 */
	public function getCode() {
		$code  = '<script type="text/javascript" src="//static.criteo.net/js/ld/ld.js" async="true"></script>' . PHP_EOL;
		$code .= '<script type="text/javascript">' . PHP_EOL;
		$code .= 'window.criteo_q = window.criteo_q || [];' . PHP_EOL;
		$code .= 'window.criteo_q.push(' . implode(",\n", $this->params) . ');' . PHP_EOL;
		$code .= '</script>' . PHP_EOL;

		return $code;
	}


}
