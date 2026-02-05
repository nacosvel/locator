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

### Config

```php
return [
    'default' => 'alipay',
    'alipay'  => [
        'default'          => '2021004102600103',
        '2021004102600102' => [
            'app_id' => '2021004102600102****',
        ],
        '2021004102600103' => [
            'app_id' => '2021004102600103****',
        ],
    ],
    'wechat'  => [
        'mch_id' => '190000****',
    ],
];
```

### Use Cases

```php
use Nacosvel\Locator\Concerns\HasAdapter;
use Nacosvel\Locator\Contracts\Adapter;
use Nacosvel\Locator\MultipleManager;

class Payment implements Adapter
{
    use HasAdapter;

    public function __construct(
        protected string $name,
        protected array $config,
    ) {
        //
    }
}

$config  = require __DIR__ . '/config.php';
$manager = new MultipleManager($config);

$manager->extend('alipay', function (string $name, array $config) {
    return new Payment($name, $config);
});

$manager->extend('wechat', function (string $name, array $config) {
    return new Payment($name, $config);
});

var_dump(
    $manager->instance('alipay')->getName(),
    $manager->instance('alipay')->getDefaultConfig(),
    $manager->instance('alipay')->getConfig(),
    $manager->instance('wechat')->getName(),
    $manager->instance('wechat')->getDefaultConfig(),
    $manager->instance('wechat')->getConfig(),
);
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
