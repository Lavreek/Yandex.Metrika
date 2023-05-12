<?php

require_once __DIR__ . "/iSettings.php";

class Remove implements iSettings
{
    public function __construct(array $options)
    {
        if (isset($options['f'])) {
            $files = array_diff(scandir(self::settings_path), ['.', '..']);

            foreach ($files as $file) {
                unlink(self::settings_path . "/" . $file);
            }

            die("\n Удаление завершено \n");
        }

        foreach ($options as $option => $v) {
            switch ($option) {
                case 'l' : {
                    $this->removeFile(self::token_default_file);
                    $this->echoResult("токена");
                    break;
                }
                case 'c' : {
                    $this->removeFile(self::counter_default_file);
                    $this->echoResult("счётчика");
                    break;
                }
            }
        }
    }

    private function echoResult($value, bool $echo = true) : string|null
    {
        $text = "\n Удаление %s завершено. \n";

        if ($echo) {
            echo sprintf($text, $value);
        } else {
            return sprintf($text);
        }

        return null;
    }

    private function removeFile($filename) : void
    {
        unlink(self::settings_path . "/". $filename);
    }
}