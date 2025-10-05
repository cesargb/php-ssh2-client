# A simple PHP library for SSH

A simple PHP library for establishing SSH connections using the `ext-ssh2` extension.

## Usage

``` php
require 'vendor/autoload.php';

use Cesargb\Ssh\Client;

$sshClient = new Client('your-ssh-server.com');

$sshSession = $sshClient->connect();

if (! $sshSession->isConnected()) {
    die('Connection failed');
}

$fingerprint = $sshSession->fingerPrint();
echo "Server Fingerprint: {$fingerprint}\n";

$sshSession->withAuthKey('username', '/path/to/public/key.pub', '/path/to/private/key');

if (! $sshSession->isAuthenticated()) {
    $sshSession->disconnect();

    die('Authentication failed');
}

$commandOutput = $sshSession->exec('ls -la');
echo "Command Output:\n{$commandOutput}\n";


```

## Testing

``` bash
composer test
```
