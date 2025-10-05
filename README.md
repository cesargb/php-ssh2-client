# A simple PHP library for SSH

A simple PHP library for establishing SSH connections using the `ext-ssh2` extension.

## Usage

``` php
require 'vendor/autoload.php';

use Cesargb\Ssh\Client;

$ssh = new Client('your-ssh-server.com', 'username');
$connection = $ssh->connect();

if (! $connection->isConnected()) {
    die('Connection failed');
}

$fingerprint = $connection->fingerPrint();
echo "Server Fingerprint: {$fingerprint}\n";

$connection->disconnect();
```

## Testing

``` bash
composer test
```
