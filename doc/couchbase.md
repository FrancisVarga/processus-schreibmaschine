Couchbase Handler
=================

[Couchbase](http://www.couchbase.com/) is open source NoSQL for mission-critical systems. Discover why developers around the world trust Couchbase Server to deliver the flexibility, scalability, and performance they need for their interactive web applications.

Usage:
------

```php
<?php
require __DIR__ . "/../../vendor/autoload.php";

use Monolog\Logger;
use Monolog\Handler\CouchbaseHandler;

$error = array(
    "expireTime" => 0,
    "params"     => array(
        array(
            "message"  => "This is bullshit to.",
            "created"  => microtime(true),
            "someData" => mt_rand(0, 49344409875093475),
            "user"     => array(
                "firstname" => "Francis",
                "lastname"  => "Varga",
                "name"      => "Francis Varga",
                "address"   => "foobar street 1234567890 Berlin",
                "bio"       => "Awesome shit",
                "email"     => "foobar[at]barfoo.com",
            ),
        )
    )
);

$logger = new Logger("couchbase");
$logger->pushHandler(new CouchbaseHandler());
$logger->err("error message", $error);
```

Formatter:
-----------

**Key:**
```
strtolower{channel}: strtolower{level_name}:md5{uniqid}
```

**Code:**
```php
$memId      = implode(":", array($record['channel'], strtolower($record['level_name']), md5(uniqid(null, true))));
```

The key generation is very important in this. Sure i can remove channel and level_name it's in the document, it's only a flavor to easy read key structures and prevent collision in the key.

Couchbase 2.0 Views:
--------------------

**View for all your channels**
```js
function (doc, meta) {
  if(doc.channel)
  {
      emit(doc.channel, null);
  }
}
```

**View for all your levels**
```js
function (doc, meta) {
  if(doc.level)
  {
      emit(doc.level, null);
  }
}
```

**View by time**
```js
function (doc, meta) {
  if(doc.channel && doc.datetime.date)
  {
    emit([doc.datetime.date, doc.level], null);
  }
}
```

If you need more info's about views and how to query [Couchbase](http://www.couchbase.com/) here are some helpfully links:

- http://www.couchbase.com/docs/couchbase-manual-2.0/couchbase-views.html
- http://wiki.apache.org/couchdb/Introduction_to_CouchDB_views

Any question? Feel free to open an issue!

