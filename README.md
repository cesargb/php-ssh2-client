# A simple PHP library for SSH

A simple PHP library for establishing SSH connections using the `ext-ssh2` extension.

## Usage

``` php
require 'vendor/autoload.php';

use Cesargb\Ssh\Client;

$sshClient = new Client('your-ssh-server.com');

$sshSession = $sshClient->connect();

$fingerprint = $sshSession->fingerPrint();
echo "Server Fingerprint: {$fingerprint}\n";

$sshSession->withAuthPublicKey('username', '/path/to/public/key.pub', '/path/to/private/key');

$commandOutput = $sshSession->exec('ls -la');
echo "Command Output:\n{$commandOutput}\n";

$sshSession->disconnect();
```

## Testing

``` bash
composer test
```
