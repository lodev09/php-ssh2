PHP SSH2
============================

Wrapper class for PHP's [SSH2 extension](http://php.net/manual/en/book.ssh2.php). The base class was created by [Jamie Munro](https://www.sitepoint.com/author/jmunro/) taken from [this article](https://www.sitepoint.com/using-ssh-and-sftp-with-php/).

## Installation
```term
$ composer require lodev09/php-ssh2
```

## Usage
```php

// connect
$auth = new \SSH2\Password(SFTP_USER, SFTP_PASSWORD);
$sftp = new \SSH2\SFTP(SFTP_HOST, $auth);

if ($sftp->is_connected() && $sftp->is_authenticated()) {
	// upload
	$sftp->put('/path/to/my/local/file', '/remote/file');

	// download
	$sftp->get('/remote/file', '/local/destination/file');
}
```

### SFTP
Common helper methods includes:
- `SFTP::mv` - move remote file
- `SFTP::rm` - delete remote file
- `SFTP::list` - list remote files
- `SFTP::is_dir` - check if path is a directory
- `SFTP::exists` - check if path exists

Other native methods can be called as well for example:
```php
// ssh2_sftp_mkdir
$sftp->mkdir(...);
```

### SCP
Just a pure wrapper of the native `ssh2_scp_xxx` functions.
```php
// ssh2_scp_recv
$scp->recv(...);
```

## Feedback
All bugs, feature requests, pull requests, feedback, etc., are welcome. Visit my site at [www.lodev09.com](http://www.lodev09.com "www.lodev09.com") or email me at [lodev09@gmail.com](mailto:lodev09@gmail.com)

## Credits
&copy; 2018 - Coded by Jovanni Lo / [@lodev09](http://twitter.com/lodev09)

## License
Released under the [MIT License](http://opensource.org/licenses/MIT).
See [LICENSE](LICENSE) file.
