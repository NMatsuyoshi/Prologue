<?php
App::uses('AppModel', 'Model');

class GachaResult extends AppModel {
	public $useTable = 't_prologue_gacha_result';
	
	public function get($user_id, $item_id) {
		$tmp['GachaResult'] = array(
			'user_id' => $user_id,
			'result' => $item_id,
		);
		$this->create();
		$this->save($tmp);
		return $this->id;
	}
}
?>
