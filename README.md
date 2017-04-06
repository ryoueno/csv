# PHP Library for using csv file (｀・ω・´)

## Usage

#### [0] Get csv ready.

```csv
    id, name,
    1, "UDON",
    2, "SHUSHI",
    ・
    ・
    ・
```

#### [1] Make src instance just to set your path of csv file.

```php
    $src = new \Gojiro\Src('path/to/MyCSV.csv');
```

#### [2] Make instance of csv by setting it.

```php
    $csv = new \Gojiro\Csv($src);
```

#### [3] Get data.

```php
    $csv->get();
    /*
        [
            0 => [
                'id' => 1,
                'name' => 'UDON',
            ],
            1 => [
                'id' => 2,
                'name' => 'SHUSHI',
            ]
            .
            .
            .
        ]
    */

```

#### [4] You can use select/where query like DB language.

```php
    $csv->select(['name'])->where->(['id' => 2])->get();
    /*
        [
            1 => [
                'name' => 'SHUSHI',
            ]
        ]
    */
```
