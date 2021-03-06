# PSR-7 фреймворк 

Почитать:
https://habr.com/ru/post/258423/
https://habr.com/ru/post/250343/

===
http://5minphp.ru/ - хорошо послушать!!!
https://ota-solid.now.sh/ - SOLID интерактивный курс
https://5minphp.ru/episode3/

Экзамен по синтаксису**
http://www.blueshoes.org/en/developer/syntax_exam/
https://www.php.net/manual/ru/types.<comparisons class="php"></comparisons>
===

{
составить перечень понятий и терминов, какие знаю/не знаю/выучу...
TL;DR (англ. too long; didn't read
}

## 1/7: Структура и работа с HTTP

Понятие **компонентный фреймворк**

*Фреймворк*

- Библиотека - пишем проект и подключаем необ. библиотеки...
- Фреймворк - устанавливаем фреймворк (каркас), помещаем в него код, подключаем при необходимости библиотеки

Схема:
```
  Фреймворк
      Проект
          Библиотека 1
          Библиотека 1
```

*Компонентный*

JavaScript (никому не интересный и делали часики) -> JQuery (посложнее задачи) -> NodeJS + NPM + WebPack (сборка библиотек, сжатие и т.п.).

Благодаря этому JS захватил долю рынка разработки. Сейчас делают все кроме ОС и драйверов.

PHP (маленький шаблонизатор) -> PHP4 (зачатки ООП) -> 2004 PHP5 (OOP, Interface) -> Социальный кодинг ГитХаб (удобно выкладывать и изменять) -> 2012 Composer (следуя мировым трендам, теперь удобно скачивать наборы библиотек и разворота) -> Анонимные функции + Типизация

[сделать тайм-лайн PHP эволюции, соотношение с другими разработками]

Примеры компонент:

Monolog/Monolog (логгер)
https://github.com/Seldaek/monolog

guzzle/guzzle (замена cURL и file_get_content())
https://github.com/guzzle/guzzle

Плюсы компонтного подхода:
- не надо изобретать велосипедов;
- быстро;

Минусы:
- требуется проф-м для создания компонент лучшими практиками;

### Перейдем к созданию проектов

php -S localhost:8080 -t public public/index.php

localhost:8080/index.php?name=Mike

Смотрим результаты запроса:
- REST client PHPStorm
- HTTP client
- Chrome Dev Tools

Функция getLang - с глобальными переменными внутри... 
```php
// Глоб. переменные внутри
$lang = getLang('en');
```

Но лучше передавать в виде аргументов, п.ч. удобнее тестировать потом и передавать фейковые данные.
```php
$lang = getLang($_GET, $_COOKIE, $_SERVER, 'en');
```

Тянуть паровоз значений - тяжело...
```php
$request = [
  'get' => $_GET,
  'server' => $_SERVER,
];
$lang = getLang($request, 'en');
```

Массивы неудобны из-за ошибок с ключами и нет автоподстановки. Воспользуемся объектом:
```php
class Request
{
  public function getCookies(): array
  {
    return $_COOKIE;
  }
}
```

### Создание структуры каталога

`src/Framework/Http/Request.php`

Почему папка Framework? Потому что в папке App будет само приложение.

Папка Http там все, что касается работы с HTTP.

Такая запись позволяет без абсолютных путей подключать файлы. Все будет смотреться/браться из корневой папки.
```php
chdir(dirname(__DIR__));
require 'src/Framework/Http/Request.php';
```

### Добавляем Composer

```
composer init
```

Установим систему безопасности пакетов (от старых библиотек):
```
composer require roave/security-advisories:dev-master
```

Но композер ничего не знает про папку src.

PSR-4 - папки и нэймспейсы должны называться одинаково.

Добавляем запись autoload и обновляем композер.
```
composer dump-autoload
```

### Добавляем тестирование

- Устанавливаем `phpunit`
- Добавляем папку `test`
- Добавляем файл `phpunit.xml.dist`
- Добавим в композер `"Tests\\": "tests/"`

```
composer require --dev phpunit/phpunit

// ставим ДЕВ, только для разработки

// на ПРОД будет команда без установки тестов
composer install --no-dev
```

Как работать с `phpunit.xml.dist`? По умолчанию PHPUnit смотрит файл `phpunit.xml`, если не находит, то смотрит глобальный `phpunit.xml.dist`. В гитигнор надо добавлять `phpunit.xml` и у себя локально делать любые настройки, кт не попадут другим разработчикам.

Посмотрим, что в этом файле:
```
bootstrap="./vendor/autoload.php" - перед загрузкой берем все классы из автолоада

<testsuites>
        <testsuite name="Test Suite">
            <directory>./tests</directory> - директория с тестами

<whitelist> - белый список, чтобы не засосало папку Вендор :)
```

Чтобы в каждом тесте не обнулять параметры Гет и Пост, делаем метод setUp(), кт будет это делать автоматически при каждом запуске.
