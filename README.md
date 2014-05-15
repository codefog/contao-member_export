member_export Contao Extension
==============================

Allows to export the member data in various formats. Currently supported formats:

- CSV (.csv)
- Excel5 (.xls)
- Excel2007 (.xlsx)

You can also create your own export format:

```php
// config/config.php
$GLOBALS['MEMBER_EXPORT_FORMATS']['my_format'] = array('MyClass', 'exportMyFormat');

// languages/en/tl_member.php
$GLOBALS['TL_LANG']['tl_member']['export_format_ref']['my_format'] = 'MyFormat (.myf)';

// classes/MyClass.php
public function exportMyFormat($blnHeaderFields, $blnRawData)
{
    $objWriter = new MyWriter();
    $this->exportFile($objWriter, $blnHeaderFields, $blnRawData);
}
```

### Contao compatibility
- Contao 3.2

### Available languages
- English
- Polish

### Support us
We put a lot of effort to make our extensions useful and reliable. If you like our work, please support us by liking our [Facebook profile](http://facebook.com/Codefog), following us on [Twitter](https://twitter.com/codefog) and watching our [Github activities](http://github.com/codefog). Thank you!

### Copyright
The extension was developed by [Codefog](http://codefog.pl) and is distributed under the Lesser General Public License (LGPL). Feel free to contact us using the [website](http://codefog.pl) or directly at info@codefog.pl.