# PHP SSH2 Client

A PHP SSH2 client wrapper providing a clean and modern interface to the ssh2 extension.

## Table of Contents

- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [Authentication Methods](#authentication-methods)
  - [Password Authentication](#password-authentication)
  - [Public Key Authentication](#public-key-authentication-with-passphrase)
  - [Agent-Based Authentication](#agent-based-authentication)
- [Executing Commands](#executing-commands)
  - [Basic Command Execution](#basic-command-execution)
  - [Exception Handling with throw()](#exception-handling-with-throw)
- [SCP File Transfers](#scp-file-transfers)
  - [Uploading Files](#uploading-a-file)
  - [Downloading Files](#downloading-a-file)
- [Testing](#testing)

## Installation

Install the package via Composer:

``` bash
composer require cesargb/ssh2-client
```

**Requirements:**
- PHP 8.2 or higher
- PHP SSH2 extension (`ext-ssh2`)

## Basic Usage

``` php
require 'vendor/autoload.php';

use Cesargb\Ssh\Ssh2Client;

// Connect to the SSH server
$sshClient = Ssh2Client::connect(host: 'localhost');

// Get server fingerprint
$fingerprint = $sshClient->fingerPrint();
echo "Server Fingerprint: {$fingerprint}\n";

// Authenticate
$sshSession = $sshClient->withAuthPassword('username', 'password');

// Execute a command
$commandResult = $sshSession->command()->execute('ls -la');

// Check the result
if (! $commandResult->succeeded()) {
    echo "Error Output: {$commandResult->errorOutput}\n";
    exit($commandResult->getExitStatus());
}

echo "Command Output: {$commandResult->output}\n";

// Disconnect when done
$sshSession->disconnect();
```

## Authentication Methods

The library supports multiple authentication methods. All authentication methods return an `SshSession` object that you can use to execute commands or transfer files.

### Password Authentication

Authenticate using a username and password:

``` php
$sshClient = Ssh2Client::connect(host: 'example.com', port: 22);
$sshSession = $sshClient->withAuthPassword(
    username: 'root',
    password: 'root_password'
);
```

### Public Key Authentication with Passphrase

Authenticate using SSH key pairs:

``` php
$sshClient = Ssh2Client::connect(host: 'example.com', port: 22);
$sshSession = $sshClient->withAuthPublicKey(
    username: 'root',
    publicKey: '/path/to/public/key.pub',
    privateKey: '/path/to/private/key',
    passphrase: 'passphrase if required'  // Optional, use empty string if no passphrase
);
```

### Agent-Based Authentication

Authenticate using the SSH agent:

``` php
$sshClient = Ssh2Client::connect(host: 'example.com', port: 22);
$sshSession = $sshClient->withAuthAgent('username');
```

## Executing Commands

### Basic Command Execution

Execute commands and check the results manually:

``` php
// Connect and authenticate
$sshSession = Ssh2Client::connect(host: 'example.com', port: 22)
    ->withAuthPassword('username', 'password');

// Execute a command
$result = $sshSession->command()->execute('ls -l');

// Check if the command succeeded
if ($result->succeeded()) {
    echo "Success: {$result->output}\n";
} else {
    echo "Failed: {$result->errorOutput}\n";
    echo "Exit code: {$result->getExitStatus()}\n";
}

// Disconnect when done
$sshSession->disconnect();
```

**Result properties:**
- `$result->succeeded()` - Returns `true` if the command executed successfully (exit status 0)
- `$result->getExitStatus()` - Returns the exit status code of the command (e.g., 0 for success, 127 for command not found)
- `$result->output` - Contains the command output from stdout
- `$result->errorOutput` - Contains any error output from stderr
- `$result->command` - The command that was executed

### Exception Handling with throw()

For cleaner error handling, you can use the `throw()` method to automatically throw exceptions when commands fail:

``` php
use Cesargb\Ssh\Exceptions\SshCommandException;

try {
    // Enable automatic exception throwing
    $sshSession = Ssh2Client::connect(host: 'example.com', port: 22)
        ->withAuthPassword('username', 'password')
        ->throw();  // Enable exception mode
    
    // Execute commands - will throw exception on failure
    $result = $sshSession->command()->execute('ls -l');
    echo "Success: {$result->output}\n";
    
    // This command will throw an exception if it fails
    $result = $sshSession->command()->execute('some-command');
    
} catch (SshCommandException $e) {
    echo "Command failed: {$e->getMessage()}\n";
    echo "Exit code: {$e->result->getExitStatus()}\n";
    echo "Error output: {$e->result->errorOutput}\n";
} finally {
    $sshSession->disconnect();
}
```

**Benefits of using `throw()`:**
- Eliminates the need for manual success checks after each command
- Provides cleaner error handling with try-catch blocks
- The exception contains the full `ExecResult` object with all command details
- Ideal for scripts where command failures should stop execution

**Note:** When using `throw()`, any command that returns a non-zero exit status will throw a `SshCommandException`.

## SCP File Transfers

Transfer files securely between local and remote systems using SCP (Secure Copy Protocol).

### Uploading a File

Upload a local file to the remote server:

``` php
$sshSession = Ssh2Client::connect(host: 'example.com', port: 22)
    ->withAuthPassword('username', 'password');

$scpResult = $sshSession
    ->scp()
    ->upload('/local/path/to/file.txt')
    ->to('/remote/path/to/file.txt');

if ($scpResult->succeeded()) {
    echo "File uploaded successfully.\n";
} else {
    echo "File upload failed.\n";
}

$sshSession->disconnect();
```

### Downloading a File

Download a file from the remote server to your local system:

``` php
$sshSession = Ssh2Client::connect(host: 'example.com', port: 22)
    ->withAuthPassword('username', 'password');

$scpResult = $sshSession
    ->scp()
    ->download('/remote/path/to/file.txt')
    ->to('/local/path/to/file.txt');

if ($scpResult->succeeded()) {
    echo "File downloaded successfully.\n";
} else {
    echo "File download failed.\n";
}

$sshSession->disconnect();
```

**Note:** SCP operations also support the `throw()` method for automatic exception handling, similar to command execution.

## Testing

``` bash
composer test
```
