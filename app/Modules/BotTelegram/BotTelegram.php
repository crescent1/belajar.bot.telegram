<?php

namespace App\Modules\BotTelegram;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BotTelegram
{
    /**
     * use guzzle
     *
     * @var \GuzzleHttp\Client $http
     */
    private $http;

    /**
     * set base uri
     *
     * @var mixed|\Illuminate\Config\Repository
     */
    protected $base_uri;


    public function __construct()
    {
        $this->base_uri = config('bottelegram.base_uri') . 'bot' . config('bottelegram.token') . '/';

        $this->http = new Client([
            'base_uri' =>  $this->base_uri,
        ]);

    }

    /**
     * uppdate to .env
     *
     * @param string $token
     * @return void
     */
    public function updateENV(string $token)
    {
        /**
         * @var string
         */
        $key = 'BOTTELEGRAM_TOKEN=';

        /**
         * @var string
         */
        $oldValue = config('bottelegram.token');

        /**
         * @var string
         */
        $newValue = $token;

        /**
         * @var string $path
         */
        $path = base_path('.env');

        if (file_exists($path)) {

            if($oldValue == '') {

                $oldValue = 'old';
            }

            file_put_contents($path, str_replace(
                $key . $oldValue, $key . $newValue, [file_get_contents($path)]
            ));
        }
    }

    /**
     * Setting untuk mengaktifkan fungsi webhook pada telegram bot
     *
     * @return array
     */
    public function setWebhook()
    {
        $response = $this->http->post('setWebhook', [
            'form_params' => [
                'url' => config('bottelegram.webhook_url')
            ],

        ]);

        /**
         * dapatkan data dari request ke bot API
         * @var string $result
         */
        $result = $response->getBody()->getContents();

        /**
         * json_dcode data
         *
         * @var array $data
         */
        $data = BTMessages::decodeMessage($result);

        return $data;

    }

    /**
     * kirim pesan dengan method sendMessage
     *
     * @param array $sendMessage
     * @return void
     */
    public function sendMessage(array $sendMessage)
    {
        $this->http->post('sendMessage', [
            'form_params' => $sendMessage
        ]);
    }

    /**
     * delete pesan yang tidak sesuai/tidak teridentifikasi
     *
     * @param array $deleteMessage
     * @return void
     */
    public function deleteMessage(array $deleteMessage)
    {
        $this->http->post('deleteMessage', [
            'form_params' => $deleteMessage
        ]);
    }

    /**
     * mengirim pesan dengan menampilkan photo
     *
     * @param array $sendPhoto
     * @return void
     */
    public function sendPhoto(array $sendPhoto)
    {
        $this->http->post('sendPhoto', [
            'form_params' => $sendPhoto
        ]);
    }

    /**
     * kirim balasan untuk callback query
     * lihat detal di https://core.telegram.org/bots/api#callbackquery -> 'NOTE'
     *
     * @param array $answerCallbackQuery
     * @return void
     */
    public function answerCallbackQuery(array $answerCallbackQuery)
    {
        $this->http->post('answerCallbackQuery',  [
            'form_params' => $answerCallbackQuery
        ]);
    }

    /**
     * kirimkan balasan edit pesan
     *
     * @param array $editMessageText
     * @return void
     */
    public function editMessageText(array $editMessageText)
    {
        $this->http->post('editMessageText', [
            'form_params' => $editMessageText
        ]);
    }

    /**
     * digunakan hanya untuk mengedit keyboard saja dan tidak mengganti pesan
     *
     * @param array $editMessageReplyMarkup
     * @return void
     */
    public function editMessageReplyMarkup(array $editMessageReplyMarkup)
    {
        $this->http->post('editMessageReplyMarkup', [
            'form_params' => $editMessageReplyMarkup
        ]);
    }

    /**
     * untuk mengedit media,
     * bisa berupa photo, video, dokumen dll
     *
     * @param array $editMessageMedia
     * @return void
     */
    public function editMessageMedia(array $editMessageMedia)
    {
        $this->http->post('editMessageMedia', [
            'form_params' => $editMessageMedia
        ]);
    }

    /**
     * mengirim photo atau video dalam bentuk album range data(2-10)
     *
     * @param array $sendMediaGroup
     * @return void
     */
    public function sendMediaGroup(array $sendMediaGroup)
    {
        $this->http->post('sendMediaGroup', [
            'form_params' => $sendMediaGroup
        ]);
    }

}
