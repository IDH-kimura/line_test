<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LINE\LINEBot;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\SignatureValidator;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Exception;

class LineTestController extends Controller
{
    //
    public function callback (Request $request)
	{
		$lineAccessToken = env('LINE_ACCESS_TOKEN', "");
		$lineChannelSecret = env('LINE_CHANNEL_SECRET', "");

		// �����̃`�F�b�N
		// $signature = $request->headers->get(HTTPHeader::LINE_SIGNATURE);
		// if (!SignatureValidator::validateSignature($request->getContent(), $lineChannelSecret, $signature)) {
			// TODO �s���A�N�Z�X
		// 	return;
		// }

		$httpClient = new CurlHTTPClient ($lineAccessToken);
		$lineBot = new LINEBot($httpClient, ['channelSecret' => $lineChannelSecret]);

		try {
			// �C�x���g�擾
			$events = $lineBot->parseEventRequest($request->getContent(), $signature);

			foreach ($events as $event) {
				// �n���[�Ɖ�������
				$replyToken = $event->getReplyToken();
				$textMessage = new TextMessageBuilder("�n���[");
				$lineBot->replyMessage($replyToken, $textMessage);
			}
		} catch (Exception $e) {
			// TODO ��O
			return;
		}

		return;
	}
}
