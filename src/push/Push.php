<?php

require_once ROOT_PATH . "/src/settings/iSettings.php";

class Push implements iSettings
{
    /** @var string $file | Имя файла */
    private string $file;

    /** @var string $all | Путь к каталогу с файлами */
    private string $all;

    const push_allowed_params = ['f' => 'file', 'a' => 'all'];

    const response_log_path = self::settings_path . "/response.log";

    private string $url;

    private string $token;

    private string $mode;

    public function __construct(array $options)
    {
        foreach (self::push_allowed_params as $option => $variable) {
            if (isset($options[$option])) {
                $this->$variable = $options[$option];
            }
        }

        if (isset($options['m'])) {
            $this->setMode($options['m']);
        }

        $this->setUrl();
    }

    public function setMode(string $mode) : void
    {
        $this->mode = $mode;
    }

    public function getMode() : string
    {
        if (!empty($this->mode)) {
            return $this->mode;
        }

        return "SAVE";
    }

    public function pushFile()
    {
        $filedata = file_get_contents($this->file);
        $filename = basename($this->file);

        $boundary = uniqid();
        $calls = join(PHP_EOL, explode("\n", $filedata));

        $data = $this->setData($boundary, $filename, $calls);

        $headers = array(
            "Content-Type: multipart/form-data; boundary=------------------------$boundary",
            "Content-Length: ".strlen($data),
            "Authorization: OAuth " . $this->token,
        );

        $response = $this->httpRequest($headers, $data);

        if ($response) {
            file_put_contents(self::response_log_path, "\n\n " . $this->file . " \n\n $response \n\n", FILE_APPEND);
        }
    }

    public function pushDirectory()
    {
        $files = array_diff(scandir($this->all), ['.', '..']);

        foreach ($files as $file) {
            $filepath = $this->all . "/" . $file;

            $filedata = file_get_contents($filepath);
            $filename = basename($filepath);

            $boundary = uniqid();
            $calls = join(PHP_EOL, explode("\n", $filedata));

            $data = $this->setData($boundary, $filename, $calls);

            $headers = array(
                "Content-Type: multipart/form-data; boundary=------------------------$boundary",
                "Content-Length: ".strlen($data),
                "Authorization: OAuth " . $this->token,
            );

            $response = $this->httpRequest($headers, $data);

            if ($response) {
                file_put_contents(self::response_log_path, "\n\n $file \n\n $response \n\n", FILE_APPEND);
            }
        }
    }

    private function httpRequest($headers, $data) : string|bool
    {
        $ch = curl_init($this->url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    private function setUrl() : void
    {
        $mode = $this->getMode();

        echo "\n Register new URL with mode `$mode` \n";
        $sample = "https://api-metrika.yandex.net/cdp/api/v1/counter/%s/data/simple_orders?merge_mode=$mode&oauth_token=%s";

        $token_path = self::settings_path . "/" . self::token_default_file;
        $counter_path = self::settings_path . "/" . self::counter_default_file;

        if (file_exists($token_path) and file_exists($counter_path)) {
            ['access_token' => $this->token] = json_decode(file_get_contents($token_path), true);
            $counter = file_get_contents($counter_path);

            $this->url = sprintf($sample, $counter, $this->token);
        } else {
            die("\n Завершите настроку репозитория. \n");
        }
    }

    private function setData($boundary, $filename, $calls) : string
    {
        $data = "--------------------------$boundary\x0D\x0A";
        $data .= "Content-Disposition: form-data; name=\"file\"; filename=\"$filename\"\x0D\x0A";
        $data .= "Content-Type: text/csv\x0D\x0A\x0D\x0A";
        $data .= $calls."\x0A\x0D\x0A";
        $data .= "--------------------------$boundary--";

        return $data;
    }
}