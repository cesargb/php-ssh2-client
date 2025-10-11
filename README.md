# PHP SSH2 Client

A PHP SSH2 client wrapper providing a clean and modern interface to the ssh2 extension.

## Usage

``` php
require 'vendor/autoload.php';

use Cesargb\Ssh\Ssh2Client;

$sshClient = new Ssh2Client()->connect(host: 'localhost', port: 22);

$fingerprint = $sshClient->fingerPrint();
echo "Server Fingerprint: {$fingerprint}\n";

// $sshClient->withAuthPassword('username', 'password');
$sshClient->withAuthPublicKey('username', '/path/to/public/key.pub', '/path/to/private/key');

$commandResult = $sshClient->exec('ls -la');

$sshClient->disconnect();

if (! $commandResult->success()) {
    echo "Error Output: {$commandResult->errorOutput}\n";
    exit($commandResult->getExitStatus());
}

echo "Command Output: {$commandResult->output}\n";

```

## Testing

``` bash
composer test
```
