<?php
App::uses('AppModel', 'Model');

class TwitterUser extends AppModel {
	public $useTable = 't_twitter_user';
	
	public function loginUser( $oauth_token, $oauth_token_secret, $screen_name ) {
		$ret = $this->find('first', array('conditions' => array('oauth_token' => $oauth_token, 'oauth_token_secret' => $oauth_token_secret)));
		if ( count( $ret ) == 0 ) {
			$tmp = array(
				'last_lot_num' => 5,
				'total_lot_num' => 0,
				'oauth_token' => $oauth_token,
				'oauth_token_secret' => $oauth_token_secret,
				'screen_name' => $screen_name,
			);
			$save_data['TwitterUser'] = $tmp;
			$this->save($save_data);
			$tmp['id'] = $this->id;
			return $tmp;
		} else {
			return $ret['TwitterUser'];
		}
	}
	
	public function lot( $user_data ) {
		if ( $user_data['last_lot_num'] > 0 ) {
			$user_data['last_lot_num'] -= 1;
			$user_data['total_lot_num'] += 1;
			unset( $user_data['modified'] );
			$this->id = $user_data['id'];
			$this->save( array('TwitterUser' => $user_data) );
			return true;
		} else {
			return false;
		}
	}
	
	public function tweetFinish( $oauth_token, $oauth_token_secret ) {
		$ret = $this->find('first', array('conditions' => array('oauth_token' => $oauth_token, 'oauth_token_secret' => $oauth_token_secret)));
		if ( count( $ret ) > 0 ){
			$ret['TwitterUser']['last_lot_num'] = 5;
			unset( $ret['TwitterUser']['modified'] );
			$this->save( $ret );
			return $ret['TwitterUser'];
		}
		return false;
	}
	
	public function getTotalNum() {
		$sql = 'SELECT SUM(total_lot_num) AS total FROM t_twitter_user';
		$ret = $this->query( $sql );
		return $ret[0][0]['total'];
	}
	
}
?>
