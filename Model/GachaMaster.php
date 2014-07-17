<?php
App::uses('AppModel', 'Model');

class GachaMaster extends AppModel {
	public $useTable = 'm_prologue_gacha_lot';
	
	public function lot($prologue_id, $num=0) {
		$ret = $this->find('all', array('conditions' => $prologue_id));
		$total_weight = 0;
		$table = array();
		foreach ( $ret as $row ) {
			$v = $row['GachaMaster'];
			//“ÁÜ‚Í‰ñ‚¹‚Î‰ñ‚·‚Ù‚Ç“–‚½‚è‚â‚·‚­
			if ( $v['special_flg'] ) {
				$v['weight'] = $num;
			}
			$total_weight += $v['weight'];
			$table[] = $v;
		}
		
		$seed = mt_rand(1, $total_weight);
		foreach ( $table as $row ) {
			if ( $seed > $row['weight'] ) {
				$seed -= $row['weight'];
			} else {
				return $row;
			}
		}
		
	}
}
?>
