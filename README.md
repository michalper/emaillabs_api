# emaillabs_api

Unofficial PHP client for the [EmailLabs](https://emaillabs.pl) transactional email API (HTTP Basic auth).

## Install

```bash
composer require michalper/emaillabs_api
```

## Usage

```php
$client = (new EmailLabs())->setAppKey('...')->setSecret('...');

$email = (new Email())
    ->setTo('recipient@example.com', 'Recipient Name')
    ->setSmtpAccount('your-smtp-account')
    ->setFrom('you@example.com')
    ->setFromName('Your Name')
    ->setSubject('Hello')
    ->setHtml('<p>Hello!</p>');

$response = $client->sendMail($email);
$response = $client->sendEmailWithTemplate($email); // with setTemplateId()

$opens = $client->getOpens();
$clicks = $client->getClicks();
$client->addBlackList($account, $email, $reason);
$client->isEmailTemporary($email);
```

Covers sending (including templates, attachments, per-recipient template variables via `setToExtended()`), delivery analytics (`getOpens`, `getClicks`, `getAgregate`), SMTP account listing, and blacklist management. Built against the [EmailLabs API docs](http://docs.emaillabs.pl/api/).

## License

GNU GPL
