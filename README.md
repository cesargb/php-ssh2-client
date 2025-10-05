# PHP SSH2 Client

A PHP SSH2 client wrapper providing a clean and modern interface to the ssh2 extension.

## Usage

``` php
require 'vendor/autoload.php';

use Cesargb\Ssh\SshClient;

$sshClient = new SshClient('your-ssh-server.com');

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
