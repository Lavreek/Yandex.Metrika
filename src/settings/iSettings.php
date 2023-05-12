<?php

interface iSettings {
    const settings_path = ROOT_PATH . "/.settings";
    const allowed_params = ['l' => 'link', 'c' => 'counter'];

    const token_default_file = "token.json";
    const counter_default_file = "counter.txt";
}