# Filefilter

Quickly scan folders for files that you want.

Easy to use library.

# Instalation

Add this line to your composer
```
{
    "require": {
        "jocolopes/filefilter": ">=1.0.0"
    }
}
```

# Usage

```
$fs = new Jocolopes\Filefilter();

print_r($fs->scan('directory/**/target/*.js'));
print_r($fs->scan('directory/**/target/**/*.js'));
print_r($fs->scan('directory/**/*.js'));
print_r($fs->scan('directory/**/example/**/*.js'));
```

# FAQ

## Do I need anything special to use this library?
You do need PHP 5.4+ and composer. You may also need to load the `vendor/autoload.php` into your project if you're not already.

## Will this work on my framework?
This library is framework agnostic, maybe the framework you're using requires some aditional setup.

If your framework uses composer you should be good to go. But let me know if you run into any troubles.

## Found a bug, what should I do?
If you have the capability to fix it yourself please do it and create a pull request.

If you don't, raise an issue on github and me or someone else will try to fix it.