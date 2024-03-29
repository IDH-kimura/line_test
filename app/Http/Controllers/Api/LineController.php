<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LINE\LINEBot;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\SignatureValidator;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Exception;

class LineController extends Controller
{
    //
	public function webhook (Request $request)
	{
		$lineAccessToken = env('LINE_ACCESS_TOKEN', "");
		$lineChannelSecret = env('LINE_CHANNEL_SECRET', "");

		// 署名のチェック
		// $signature = $request->headers->get(HTTPHeader::LINE_SIGNATURE);
		// if (!SignatureValidator::validateSignature($request->getContent(), $lineChannelSecret, $signature)) {
			// TODO 不正アクセス
		// 	return;
		// }

		$httpClient = new CurlHTTPClient ($lineAccessToken);
		$lineBot = new LINEBot($httpClient, ['channelSecret' => $lineChannelSecret]);

		try {
			// イベント取得
			$events = $lineBot->parseEventRequest($request->getContent(), $signature);

			foreach ($events as $event) {
				// ハローと応答する
				$replyToken = $event->getReplyToken();
				$textMessage = new TextMessageBuilder("ハロー");
				$lineBot->replyMessage($replyToken, $textMessage);
			}
		} catch (Exception $e) {
			// TODO 例外
			return;
		}

		return;
	}
}
