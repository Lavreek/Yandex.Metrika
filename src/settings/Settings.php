<?php

require_once __DIR__ . "/iSettings.php";

class Settings implements iSettings
{
    /** @var string $link | Адресная строка авторизации */
    private string $link;

    /** @var string $counter | Номер счётчика Яндекс.Метрика */
    private string $counter;

    public function __construct(array $options)
    {
        foreach (self::allowed_params as $option => $variable) {
            if (isset($options[$option])) {
                $this->$variable = $options[$option];
            }
        }

        $this->createSettingFolder();
    }

    public function createTokenFile()
    {
        if ($link = $this->link) {
            if (preg_match('#\#(.+)#', $link, $match)) {
                $link_params = explode('&', $match[1]);

                if (count($link_params) > 0) {
                    $json = [];

                    foreach ($link_params as $group) {
                        [$link_param, $link_value] = explode('=', $group);
                        $json += [$link_param => $link_value];
                    }

                    file_put_contents(self::settings_path . "/" . self::token_default_file, json_encode($json));
                    echo "\n Конфигурационный файл с токеном создан. \n";
                }
            }
        }
    }

    public function createCounterFile()
    {
        if ($counter = $this->counter) {
            file_put_contents(self::settings_path . "/" . self::counter_default_file, $counter);
            echo "\n Конфигурационный файл с счётчиком создан. \n";
        }
    }

    private function createSettingFolder()
    {
        if (!is_dir(self::settings_path)) {
            mkdir(self::settings_path);
        }
    }
}