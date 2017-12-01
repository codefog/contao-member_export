# Developers – Member Export bundle

1. [Installation](installation.md)
2. [Usage](usage.md)
3. [**Developers**](developers.md)


## Exclude fields from export

To exclude certain fields from the member export you can create the file `app/Resources/contao/dca/tl_member.php`
with the following content:

```php
<?php

/**
 * Exclude fields from member export
 */
$excludedFields = ['tstamp', 'password'];

foreach ($excludedFields as $field) {
    $GLOBALS['TL_DCA']['tl_member']['fields'][$field]['eval']['memberExportExcluded'] = true;
}
```

Afterwards you may need to rebuild the cache in Contao Manager or using the commands:

```
vendor/bin/contao-console cache:clear --no-warmup
vendor/bin/contao-console cache:warmup
```


## Create a custom exporter

In addition to the existing exporters you can create your own class that allows to export member data. The new service 
must implement the [Codefog\MemberExportBundle\Exporter\ExporterInterface](../src/Exporter/ExporterInterface.php) 
interface and be registered with the appropriate tag:

```yaml
services:
    app.my_exporter:
        class: AppBundle\MemberExporter\MyExporter
        public: false
        tags:
            - { name: 'codefog_member_export.exporter', priority: 128 }
```

You should also add the label that will be displayed in the dropdown format menu:

```php
# languages/en/tl_member.php
$GLOBALS['TL_LANG']['tl_member']['export_formatRef']['my_exporter'] = 'My exporter';
```
