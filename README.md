# StudioForty9 Gallery

## Features

- Repsonsive gallery
- A lightbox/quickview for photos
- Photos can belong to multiple albums
- Backend sorting similar to category product sorting
- Sortable albums
- All gallery pages have SEO titles, keywords and descriptions
- Option to show/hide breadcrumbs
- Option to show/hide a link in top.links

## Installation

### Composer

Add the package to your require list, here's a smaple composer file:

```json
{
  "require": {
    "studioforty9/gallery": "dev-master"
  },
  "repositories": [
    { "type": "composer", "url": "http://packages.firegento.com" },
    { "type": "vcs", "url": "http://github.com/studioforty9/gallery" }
  ],
  "extra":{
    "magento-root-dir": "htdocs/",
    "magento-force": true,
    "auto-append-gitignore": true,
    "magento-deploystrategy": "copy"
  }
}
```


> Don't forget to set permissions on media/gallery to 0777.


## Configuration

Once you've got the extension installed, you can configure some defaults via System => Configuration => StudioForty9 => Gallery.

You can choose to show/hide breadcrumbs. You can show/hide a link in the top.links block, you can also label that link whatever you want.

You can also set the SEO title, keywords and description for the gallery index page, all albums and media have fields for SEO title, keywords and description individually.

## Contributing

[see CONTRIBUTING file](https://github.com/studioforty9/recaptcha/blob/master/CONTRIBUTING.md)

## Licence

BSD 3 Clause [see LICENCE file](https://github.com/studioforty9/recaptcha/blob/master/LICENCE)
