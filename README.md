# PHP SSH2 Client

A PHP SSH2 client wrapper providing a clean and modern interface to the ssh2 extension.

## Basic Usage

``` php
require 'vendor/autoload.php';

use Cesargb\Ssh\Ssh2Client;

$sshClient = Ssh2Client::connect(host: 'localhost');

$fingerprint = $sshClient->fingerPrint();
echo "Server Fingerprint: {$fingerprint}\n";

$sshClient->withAuthPassword('username', 'password');

$commandResult = $sshClient->exec('ls -la');

$sshClient->disconnect();

if (! $commandResult->succeeded()) {
    echo "Error Output: {$commandResult->errorOutput}\n";

    exit($commandResult->getExitStatus());
}

echo "Command Output: {$commandResult->output}\n";
```

## Installation

``` bash
composer require cesargb/ssh2-client
```

## Authentication Methods

### Password Authentication

``` php
$sshClient = Ssh2Client::connect(host: 'example.com', port: 22);
$sshClient->withAuthPassword(
    username: 'root',
    password: 'root_password'
);
```

### Public Key Authentication with Passphrase

``` php
$sshClient = Ssh2Client::connect(host: 'example.com', port: 22);
$sshClient->withAuthPublicKey(
    username: 'root',
    publicKey: '/path/to/public/key.pub',
    privateKey: '/path/to/private/key',
    passphrase: 'passphrase if required'
);
```

### Agent-Based Authentication

``` php
$sshClient = Ssh2Client::connect(host: 'example.com', port: 22);
$sshClient->withAuthAgent('username');
```

## Executing Commands

``` php
$sshClient = Ssh2Client::connect(host: 'example.com', port: 22)
    ->withAuthPassword('username', 'password');

$result = $sshClient->exec('ls -l');

$sshClient->disconnect();

// $result->succeeded() returns true if the command executed successfully
// $result->getExitStatus() returns the exit status of the command
// $result->output contains the command output by stdout
// $result->errorOutput contains any error output by stderr
// $result->disconnect(); disconnects the SSH session
```

## SCP File Transfers

### Uploading a File

``` php
$sshClient = Ssh2Client::connect(host: 'example.com', port: 22)
    ->withAuthPassword('username', 'password');

$scpResult = $sshClient
    ->scp()
    ->upload('/local/path/to/file.txt')
    ->to('/remote/path/to/file.txt');

$sshClient->disconnect();

if (! $scpResult->succeeded()) {
    echo "File transfer failed.\n";

    exit(1);
}

echo "File transferred successfully.\n";
```

### Downloading a File

``` php
$sshClient = Ssh2Client::connect(host: 'example.com', port: 22)
    ->withAuthPassword('username', 'password');

$scpResult = $sshClient
    ->scp()
    ->download('/remote/path/to/file.txt')
    ->to('/local/path/to/file.txt');

$sshClient->disconnect();

if (! $scpResult->succeeded()) {
    echo "File transfer failed.\n";

    exit(1);
}

echo "File transferred successfully.\n";
```

## Testing

``` bash
composer test
```
