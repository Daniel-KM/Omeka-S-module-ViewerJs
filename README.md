ViewerJs (module for Omeka S)
=============================

> __New versions of this module and support for Omeka S version 3.0 and above
> are available on [GitLab], which seems to respect users and privacy better
> than the previous repository.__

[Viewer Js] is a module for [Omeka S] that allows to display common document
formats (pdf, standard office ones), via a light viewer.

. Supported formats are:

- Portable document format (pdf)
- OpenDocument Text (odt, fodt)
- OpenDocument Spreadsheet (ods, fods)
- OpenDocument Presentation (odp, fodp)

Common multimedia formats can be managed too, if they are displayable by the
browser. They are not enabled by default, because they are natively managed by
Omeka S.

- Images (jpg, png, gif)
- Audio/video (via html5)

Concretely, it includes the [ViewerJS] library, that integrates the pdf library
of Mozilla [pdf.js] and the standard office formats via [WebODF]. The images and
the audio/video are displayed via the browser itself.

Note: for office documents, the bigger they are, the bigger should be the
computer of the visitor to display them.


Installation
------------

The module uses an external library, [ViewerJS], so use the release zip to
install it, or use and init the source.

* From the zip

Download the last release [ViewerJs.zip] from the list of releases (the master
does not contain the dependency), and uncompress it in the `modules` directory.

* From the source and for development:

If the module was installed from the source, rename the name of the folder of
the module to `ViewerJs`, and go to the root module, and run:

```sh
composer install --no-dev
```

* Note about WebODF

Because the standards of the [Document Foundation] are "simple" and easy to
manage, the library [WebODF] is mainly a stylesheet. And because this is a true
standard, it is sustainable, stable and available to anyone. So it’s recommended
for any office work.


Config
------

All resources of Omeka S that are in pdf, odt, fodt, ods, fods, odp, fodp, and
txt are automatically displayed by the ViewerJs, so you have nothing to do.

Sources files can use uploaded files or url, so you can try an url such: https://github.com/Daniel-KM/ViewerJS/raw/master/test/files/presentation.odp.

Options can be set differently for the admin board or each site. Only one option
is available directly: the styles of the iframe. Other ones are managed via the
rendered.

To display the media (images, audio, video) via the ViewerJS, copy and adapt the
section `['file_renderers']['aliases']` in `config/module.config.php` in your
`config/local.config.php`.

To display the pdf with the more complete viewer provided by the module [Pdf Viewer],
you need to set its value in your local config too:

```php
            'application/pdf' => 'pdfViewer',
            'pdf' => 'pdfViewer',
```


### Advanced display in public theme

Because the viewer is integrated as a renderer and not a helper, it is possible
to display it anywhere via the media. In particular, you can use the standard
block "Media" to display it in any page. So just render the media, with possible
options, that are passed directly to the template:

```php
echo $media->render($options);
```

A block layout is available too if needed for external urls. Furthermore, a view
helper is available to render any url anywhere:

```php
$options = ['source' => 'https://example.org/my-file.odp'];
echo $this->viewerJs(null, $options);
```

### Fix with module Dropbox

When Dropbox is used as a backend for files, you may need to hack the asset file
"asset/vendor/viewerjs/viewer.js", commenting lines 305 to 309:

```js
/*
        if ( documentUrl.indexOf('?') !== -1 ) {
            documentUrl += '&contentDispositionType=attachment';
        } else {
            documentUrl += '?contentDispositionType=attachment';
        }
*/
```


Warning
-------

Use it at your own risk.

It’s always recommended to backup your files and your databases and to check
your archives regularly so you can roll back if needed.


Troubleshooting
---------------

See online issues on the [module issues] page on GitLab.


License
-------

This module is published under the [CeCILL v2.1] license, compatible with
[GNU/GPL] and approved by [FSF] and [OSI].

This software is governed by the CeCILL license under French law and abiding by
the rules of distribution of free software. You can use, modify and/ or
redistribute the software under the terms of the CeCILL license as circulated by
CEA, CNRS and INRIA at the following URL "http://www.cecill.info".

As a counterpart to the access to the source code and rights to copy, modify and
redistribute granted by the license, users are provided only with a limited
warranty and the software’s author, the holder of the economic rights, and the
successive licensors have only limited liability.

In this respect, the user’s attention is drawn to the risks associated with
loading, using, modifying and/or developing or reproducing the software by the
user in light of its specific status of free software, that may mean that it is
complicated to manipulate, and that also therefore means that it is reserved for
developers and experienced professionals having in-depth computer knowledge.
Users are therefore encouraged to load and test the software’s suitability as
regards their requirements in conditions enabling the security of their systems
and/or data to be ensured and, more generally, to use and operate it in the same
conditions as regards security.

The fact that you are presently reading this means that you have had knowledge
of the CeCILL license and that you accept its terms.

The [ViewerJS] library is published under the [GNU AGPL] license.
The [WebODF] library is published under the [GNU AGPL] license.
The [pdf.js] library is published under the [Apache] license.


Copyright
---------

[ViewerJS] and [WebODF] libraries:

* Copyright KO GmbH, 2013-2017

Javascript library [pdf.js]:

* Copyright Mozilla, 2011-2017

Module Viewer Js for Omeka S:

* Copyright Daniel Berthereau, 2017-2023 (see [Daniel-KM])


[Viewer Js]: https://gitlab.com/Daniel-KM/Omeka-S-module-ViewerJs
[Omeka S]: https://omeka.org/s
[ViewerJS]: https://viewerjs.org
[ViewerJs.zip]: https://gitlab.com/Daniel-KM/Omeka-S-module-ViewerJs/-/releases
[pdf.js]: https://mozilla.github.io/pdf.js
[WebODF]: https://github.com/kogmbh/WebODF
[Document Foundation]: https://www.documentfoundation.org
[Pdf Viewer]: https://gitlab.com/Daniel-KM/Omeka-S-module-PdfViewer
[module issues]: https://gitlab.com/Daniel-KM/Omeka-S-module-ViewerJs/-/issues
[CeCILL v2.1]: https://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[FSF]: https://www.fsf.org
[OSI]: http://opensource.org
[Apache]: https://github.com/mozilla/pdf.js/blob/master/LICENSE
[GNU AGPL]: https://www.gnu.org/licenses/agpl-3.0.html
[GitLab]: https://gitlab.com/Daniel-KM
[Daniel-KM]: https://gitlab.com/Daniel-KM "Daniel Berthereau"
