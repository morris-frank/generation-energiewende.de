<?php
	/**
	* The Controller class for the twitter widget
	*/
	class TwitterController
	{

		private $screen_name;
		private $key;
		private $secret;
		public $interval = 5;
		private $cache_file = 'twitter.cache';
		private $token_file = 'twitter.token';
		private $access_token;

		function __construct($screen_name, $key, $secret)
		{
			$this->secret = $secret;
			$this->key = $key;
			$this->screen_name = $screen_name;

			if (!file_exists($this->cache_file)) {
				$this->renewCache();
			} else {
				$incub_time = time() - filemtime($this->cache_file);
				if ($incub_time > $this->interval * 60) {
					$this->renewCache();
				}
			}
		}

		private function renewCache()
		{
			if (file_exists($this->token_file)) {
				$this->access_token = file_get_contents($this->token_file);
			} else {
				$this->reloadToken();
			}

			$timeline = $this->getTimeline($this->screen_name, 10);
			file_put_contents($this->cache_file, $timeline);
		}

		private function reloadToken()
		{
			$tokenURL = 'https://api.twitter.com/oauth2/token';

			$basic_credentials = base64_encode($this->key.':'.$this->secret);
			$tk = curl_init($tokenURL);
			curl_setopt($tk, CURLOPT_HTTPHEADER, array(
													'Authorization: Basic '.$basic_credentials,
													'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
												 )
					   );
			curl_setopt($tk, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
			curl_setopt($tk, CURLOPT_RETURNTRANSFER, true);
			$token = json_decode(curl_exec($tk));
			curl_close($tk);
			$this->access_token = $token->access_token;
			file_put_contents($this->token_file, $this->access_token);
		}

		private function getTimeline($user, $count)
		{
			$timeLineURL = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

			$tk = curl_init($timeLineURL . '?screen_name=' . $user . '&count=' . $count);
			curl_setopt($tk, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->access_token));
			curl_setopt($tk, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($tk);
			curl_close($tk);

			if (isset($response->errors)) {
				foreach ($response->errors as $key => $value) {
					if ($value->code == 89) {
						echo "Invalid token";
						$this->reloadToken();
						$response = $this->getTimeline($user, $count);
					}
				}
			}

			return $response;
		}

		public function draw()
		{
			$timeline = json_decode(file_get_contents($this->cache_file));
			$name = $timeline[0]->user->name;
			$append = "<h3>"
					. "<a class='twtr_user' href='https://twitter.com/".$this->screen_name."'>Twitter @".$this->screen_name."</a>"
					. "</h3>"
					. "<div class='twtr_timeline orbit' data-orbit data-auto-play='true' data-pause-on-hover='true' >"
					. "<ul class='orbit-container'>";
			$append .= "<button class='orbit-previous'>&#10094;&#xFE0E;</button>"
					. "<button class='orbit-next'>&#10095;&#xFE0E;</button>";
				foreach ($timeline as $tweetID => $tweet) {
					$append .= $this->drawTweet($tweet);
				}
			$append .= "</div>";
			echo $append;
		}

		private function drawTweet($tweet)
		{
			$append = "<li class='twtr_tweet orbit-slide'>"
					. "<span class='twtr_text'>";
			$drawText = $tweet->text;
			$drawMedia = '';

			if (isset($tweet->entities->hashtags)) {
				$drawText = $this->rplHashtags($drawText, $tweet->entities->hashtags);
			}

			if (isset($tweet->entities->user_mentions)) {
				$drawText = $this->rplUsers($drawText, $tweet->entities->user_mentions);
			}

			if (isset($tweet->entities->media)) {
				$drawMedia = $this->drawMedia($tweet->entities->media);
				$drawText = substr($drawText, 0, -23);
			}

			if (isset($tweet->entities->urls)) {
				$drawText = $this->rplLinks($drawText, $tweet->entities->urls);
			}

			$drawDate = $this->drawDate($tweet->created_at);

			$append .= $drawText
					.  "</span>"
					.  $drawMedia
					.  $drawDate
					.  "</li>";
			return $append;
		}

		private function rplHashtags($tweet_text, $hashtags)
		{
			foreach ($hashtags as $key => $hashtag) {
				$ht = $hashtag->text;
				$tweet_text = str_replace('#'.$ht, "<a class='twtr_hashtag' href='https://twitter.com/hashtag/".$ht."'>#".$ht."</a>", $tweet_text);
			}
			return $tweet_text;
		}

		private function rplUsers($tweet_text, $users)
		{
			foreach ($users as $key => $user) {
				$sn = $user->screen_name;
				$tweet_text = str_replace('@'.$sn, "<a class='twtr_user' href='https://twitter.com/".$sn."'>@".$sn."</a>", $tweet_text);
			}
			return $tweet_text;
		}

		private function rplLinks($tweet_text, $urls)
		{
			foreach ($urls as $key => $url) {
				$disp = $url->display_url;
				$real = $url->expanded_url;
				$orig = $url->url;
				$tweet_text = str_replace($orig, "<a class='twtr_link' href='".$real."'>".$disp."</a>", $tweet_text);
			}
			return $tweet_text;
		}

		private function drawMedia($media) {
			$append = '';
			foreach ($media as $key => $medium) {
				if ($medium->type == "photo")
				{
					$src = $medium->media_url_https;
					$wd = $medium->sizes->small->w;
					$ht = $medium->sizes->small->h;
					$alt = $medium->id_str;
					$append .= "<img class='twtr_photo' src='".$src."' height='".$ht."px' width='".$wd."' alt='".$alt."'\>";
				}
			}
			return $append;
		}

		private function drawDate($created_at) {
			$append = "<date class='twtr_date' >";
			$date = new DateTime($created_at);
			$date->setTimezone(new DateTimeZone('EUROPE/Berlin'));
			$formatted_date = $date->format('H:i, M d');
			$append .= '@' . $formatted_date;
			$append .= '</date>';
			return $append;
		}
	}


?>
