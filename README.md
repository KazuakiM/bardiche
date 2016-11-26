bardiche
===

[![Travis](https://img.shields.io/travis/KazuakiM/bardiche.svg?style=flat-square)](https://travis-ci.org/KazuakiM/bardiche)
[![Coveralls](https://img.shields.io/coveralls/KazuakiM/bardiche.svg?style=flat-square)](https://coveralls.io/github/KazuakiM/bardiche?branch=master)
[![Scrutinizer](https://img.shields.io/scrutinizer/KazuakiM/bardiche.svg?style=flat-square)](https://scrutinizer-ci.com/g/KazuakiM/bardiche/)
[![GitHub issues](https://img.shields.io/github/issues/KazuakiM/bardiche.svg?style=flat-square)](https://github.com/KazuakiM/bardiche/issues)
[![license](https://img.shields.io/github/license/KazuakiM/bardiche.svg?style=flat-square)](https://raw.githubusercontent.com/KazuakiM/bardiche/master/LICENSE)

FTP:zap:, FTPS:snowman:, SFTP:sunny: and SCP:snowflake: clients:jack_o_lantern::sparkles:

##Usage

You should check [bardiche sample repository](https://github.com/KazuakiM/bardiche-samples). And I'll write FTP sample code.

One time connection by FTP.
```php
<?php

try {
    FileClients::one(FileClientsType::BARDICHE_TYPE_FTP(), [
        'negotiation' => true,                             // options default: fallse
        'timeout'     => 90,                               // options default: 90
        'host'        => '192.168.1.1',
        'username'    => 'vagrant',
        'password'    => 'vagrant',
        'file_info'   => [
            [
                'remote_directory_path' => '/',
                'remote_file_name'      => 'fate_t_harlaown.txt',
                'local_directory_path'  => '/tmp',
                'local_file_name'       => 'fate_testarossa.txt',
                'ascii'                 => FTP_ASCII,
            ],
            //[
            //    'remote_directory_path' => '/takamachi',
            //    'remote_file_name'      => 'fate_t_harlaown.txt',
            //    'local_directory_path'  => '/tmp',
            //    'local_file_name'       => 'fate_testarossa.txt',
            //    'ascii'                 => FTP_ASCII,
            //],
        ],
        'port'     => 2224,                                // options default: 21
        'pasv'     => false,                               // options default: true
        'parallel' => 2,                                   // options default: 0
    ], FileClients::BARDICHE_UPLOAD);
} catch ( BardicheException $e ) {
    var_dump(json_decode($e->getMessage(), true));
}
```

## Features Liest

Here is the list of tested features and unsupported features:

* ssh test case. (very hard mode...)

##Author

[KazuakiM](https://github.com/KazuakiM/)

##License

This software is released under the MIT License, see LICENSE.
