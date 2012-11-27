Tracking 
===============

If you can log why also not track?

```php
require __DIR__ . "/../../vendor/autoload.php";

use Monolog\Tracking;
use Monolog\Handler\CouchbaseHandler;

$tracker = new Tracking("user");
$tracker->pushHandler(new CouchbaseHandler());
$tracker->trackEvent("levelup", "user level ups", array("userId" => 1223123, "expireTime" => 0));
```

**NOT TESTED WITH ANNOTHER HANDLER AND FORMATTER**