# Developers – Member Export bundle

1. [Installation](installation.md)
2. [Usage](usage.md)
3. [**Developers**](developers.md)


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
