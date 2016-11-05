bardiche
===

[![](https://img.shields.io/travis/KazuakiM/bardiche.svg)](https://travis-ci.org/KazuakiM/bardiche)
[![](https://img.shields.io/github/issues/KazuakiM/bardiche.svg)](https://github.com/KazuakiM/bardiche/issues)
[![](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

FTP:zap: and SCP:snowflake: clients:jack_o_lantern::sparkles:

##Usage

[bardiche sampel repository](https://github.com/KazuakiM/bardiche-samples)

One time connection by FTP.
```php
<?php

try {
    FileClients::one(FileClientsType::BARDICHE_TYPE_FTP(), [
        'negotiation' => false,
        'timeout'     => 90,
        'host'        => '',
        'username'    => '',
        'password'    => '',
        'file_info'   => [
            [
                'remote_directory_path' => '',
                'remote_file_name'      => '',
                'local_directory_path'  => '',
                'local_file_name'       => '',
            ],
        ],
        'port'  => 21,
        'pasv'  => true,
        'ascii' => true,
        'ssl'   => false,
    ], FileClients::BARDICHE_UPLOAD);
} catch ( BardicheException $e ) {
    var_dump(json_decode($e->getMessage(), true));
}
```

Serial update many file by FTP.
```php
<?php

try {
    $clients = new FileClients(FileClientsType::BARDICHE_TYPE_FTP(), [
        'negotiation' => false,
        'timeout'     => 90,
        'host'        => '',
        'username'    => '',
        'password'    => '',
        'file_info'   => [
            [
                'remote_directory_path' => '',
                'remote_file_name'      => '',
                'local_directory_path'  => '',
                'local_file_name'       => '',
            ],
        ],
        'port'  => 21,
        'pasv'  => true,
        'ascii' => true,
        'ssl'   => false,
    ]);

    $clients->upload();

    $clients->setOptions([
        'remote_file_name' => '',
        'local_file_name'  => '',
    ]);
    $clients->upload();

    $clients->__destruct();
} catch ( BardicheException $e ) {
    var_dump(json_decode($e->getMessage(), true));
}
```

## Features Liest

Here is the list of tested features and unsupported features:

* Support FTPS:snowman:
* Support SFTP:sunny:
* Retry function
* Support parallel files (Use [ftp_nb_put()](http://php.net/manual/ja/function.ftp-nb-put.php) and [ftp-nb-get](http://php.net/manual/ja/function.ftp-nb-get.php))

##Author

[KazuakiM](https://github.com/KazuakiM/)

##License

This software is released under the MIT License, see LICENSE.
