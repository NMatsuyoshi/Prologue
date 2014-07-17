<?php
// Controller/ExampleController.php
App::import('Vendor', 'OAuth/OAuthClient');

class PrologueController extends AppController {
	var $uses = array('TwitterUser', 'GachaMaster', 'GachaResult');
	
	// トップページ
	public function index() {
	}
	
	// 抽選前
	public function lot() {
		$user_data = $this->Session->read('prologue_user_data');
		$this->set('user_data', $user_data);
	}
	
	// 抽選中
	public function lotnow() {
		$user_data = $this->Session->read('prologue_user_data');
		$result = $this->GachaMaster->lot(1, $user_data['total_lot_num']);
		$this->GachaResult->get($user_data['id'], $result['id']);
		$new_user_data = $this->TwitterUser->lot($user_data);
		$ret = $this->TwitterUser->find('first', array('conditons' => array('id' => $user_data['id'])));
		$this->Session->write('prologue_user_data', $ret['TwitterUser']);
		$this->redirect(array('controller' => 'prologue', 'action' => 'finish', $result['id']));
	}
	
	// 抽選後
	public function finish($item_id) {
		$user_data = $this->Session->read('prologue_user_data');
	}
	
	// ログイン用アクション（ページなし）
	public function login() {
		$client = $this->createClient();
		$requestToken = $client->getRequestToken('https://api.twitter.com/oauth/request_token', 'http://' . $_SERVER['HTTP_HOST'] . '/prologue/callback');

		if ($requestToken) {
			$this->Session->write('twitter_request_token', $requestToken);
			$this->redirect('https://api.twitter.com/oauth/authenticate?oauth_token=' . $requestToken->key);
		} else {
			// an error occured when obtaining a request token
		}
	}

	// コールバック用アクション（ページなし）
	public function callback() {
		$requestToken = $this->Session->read('twitter_request_token');
		$client = $this->createClient();
		$accessToken = $client->getAccessToken('https://api.twitter.com/oauth/access_token', $requestToken);
		$this->Session->write('twitter_access_token', $accessToken);
		// アクセストークンを記録
		$json = $client->get($accessToken->key, $accessToken->secret, 'https://api.twitter.com/1.1/account/verify_credentials.json', array());
		$user_data = json_decode( $json, true );
		$ret = $this->TwitterUser->loginUser( $accessToken->key, $accessToken->secret, $user_data['screen_name'] );
		$this->Session->write('prologue_user_data', $ret);
		$this->redirect(array('controller' => 'prologue', 'action' => 'lot' ));
	}

	// ツイート用アクション（ページなし）
	public function tweet() {
		$client = $this->createClient();
		$accessToken = $this->Session->read('twitter_access_token');
		// ツイート
		$total_num = $this->TwitterUser->getTotalNum();
		$message = '現在通算' . $total_num . '回ガチャってます！';
		$result = $client->post($accessToken->key, $accessToken->secret, 'https://api.twitter.com/1.1/statuses/update.json', array('status' => $message));
		// ガチャを引ける回数を増やす
		$ret = $this->TwitterUser->tweetFinish( $accessToken->key, $accessToken->secret );
		$this->Session->write('prologue_user_data', $ret);
		$this->redirect(array('controller' => 'prologue', 'action' => 'lot' ));
	}

	private function createClient() {
		//コンシューマキー、コンシューマシークレットをここに記載する
		return new OAuthClient('??', '???');
	}
}
?>