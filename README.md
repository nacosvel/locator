<a id="readme-top"></a>

# Locator

Manages multiple instances, handling default selection, creation, caching, and extension.

[![GitHub Tag][GitHub Tag]][GitHub Tag URL]
[![Total Downloads][Total Downloads]][Packagist URL]
[![Packagist Version][Packagist Version]][Packagist URL]
[![Packagist PHP Version Support][Packagist PHP Version Support]][Repository URL]
[![Packagist License][Packagist License]][Repository URL]

<!-- TABLE OF CONTENTS -->
<details>
    <summary>Table of Contents</summary>
    <ol>
        <li><a href="#installation">Installation</a></li>
        <li><a href="#usage">Usage</a></li>
        <li><a href="#contributing">Contributing</a></li>
        <li><a href="#contributors">Contributors</a></li>
        <li><a href="#license">License</a></li>
    </ol>
</details>

<!-- INSTALLATION -->

## Installation

You can install the package via [Composer]:

```bash
composer require nacosvel/locator
```

<p align="right">[<a href="#readme-top">back to top</a>]</p>

<!-- USAGE EXAMPLES -->

## Usage

### config.php

```php
<?php

return [
    'default' => 'alipay',
    'alipay'  => [
        'default'          => '2021004102600103',
        // 'driver'           => AlipayPaymentManager::class,
        '2021004102600102' => [
            'app_id' => env('ALIPAY_APP_ID', '2021004102600102****'),
        ],
        '2021004102600103' => [
            'app_id' => env('ALIPAY_APP_ID', '2021004102600103****'),
        ],
    ],
    'wechat'  => [
        'mch_id' => env('WECHAT_MCH_ID', '190000****'),
    ],
];
```

### Payment::class

```php
interface Payment
{
}
```

### PaymentManager::class

```php
use Nacosvel\Locator\MultipleInstanceManager;

class PaymentManager extends MultipleInstanceManager
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    #[ReturnTypeWillChange]
    public function instance(string $name = null): Payment
    {
        return parent::instance($name);
    }
}

PaymentManager::macro('other', function () {
    //
});

$payment = new PaymentManager(require __DIR__ . '/config.php');

$payment->extend('alipay', function (array $config) {
    return new Alipay($config);
});
$payment->extend('wechat', function (array $config) {
    return new Wechat($config);
});
```

### PaymentManager::instance

```php
$payment->instance(); // alipay
```

### PaymentManager::using

```php
var_dump($payment->getDefaultInstance());// alipay
$payment->using('wechat', function () {
    // wechat
});
var_dump($payment->getDefaultInstance());// alipay
```

<!-- CONTRIBUTING -->

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">[<a href="#readme-top">back to top</a>]</p>

<!-- CONTRIBUTORS -->

## Contributors

Thanks goes to these wonderful people:

<a href="https://github.com/nacosvel/locator/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=nacosvel/locator" alt="contrib.rocks image" />
</a>

Contributions of any kind are welcome!

<p align="right">[<a href="#readme-top">back to top</a>]</p>

<!-- LICENSE -->

## License

Distributed under the MIT License (MIT). Please see [License File] for more information.

<p align="right">[<a href="#readme-top">back to top</a>]</p>

[GitHub Tag]: https://img.shields.io/github/v/tag/nacosvel/locator

[Total Downloads]: https://img.shields.io/packagist/dt/nacosvel/locator?style=flat-square

[Packagist Version]: https://img.shields.io/packagist/v/nacosvel/locator

[Packagist PHP Version Support]: https://img.shields.io/packagist/php-v/nacosvel/locator

[Packagist License]: https://img.shields.io/github/license/nacosvel/locator

[GitHub Tag URL]: https://github.com/nacosvel/locator/tags

[Packagist URL]: https://packagist.org/packages/nacosvel/locator

[Repository URL]: https://github.com/nacosvel/locator

[GitHub Open Issues]: https://github.com/nacosvel/locator/issues

[Composer]: https://getcomposer.org

[License File]: https://github.com/nacosvel/locator/blob/main/LICENSE
