eCrawling
-------------
eCrawling, an Email Address Crawler and Extractor

TABLE STRUCTURE
-----------------------

```php
CREATE TABLE IF NOT EXISTS `emails` (
  `ID` int(11) NOT NULL,
  `host` text NOT NULL,
  `emails` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
```