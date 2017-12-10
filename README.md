ViewerJs (module for Omeka S)
=============================

[ViewerJs] is a module for [Omeka S] that allows to display common document
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


Installation
------------

The module uses an external library, [viewer.js], so use the release zip to
install it, or use and init the source.

* From the zip

Download the last release [`ViewerJs.zip`] from the list of releases (the
master does not contain the dependency), and uncompress it in the `modules`
directory.

* From the source and for development:

If the module was installed from the source, rename the name of the folder of
the module to `ViewerJs`, and go to the root module, and run:

```
    npm install
    gulp
```

The next times:

```
    npm update
    gulp
```

* Note about WebODF

Because the standards of the [Document Foundation] are "simple" and easy to
manage, the library [WebODF] is mainly a stylesheet. And because this is a true
standard, it is sustainable, stable, and available to anyone.


Config
------

All resources of Omeka S that are in pdf, odt, fodt, ods, fods, odp, fodp, and
txt are automatically displayed by the ViewerJs, so you have nothing to do.

Options can be set differently for the admin board or each site. Only one option
is available directly: the styles of the iframe. Other ones are managed via the
rendered.

To display the media (images, audio, video) via the ViewerJS, add them in the
section `['file_renderers']['aliases']`.


Warning
-------

Use it at your own risk.

It’s always recommended to backup your files and your databases and to check
your archives regularly so you can roll back if needed.


Troubleshooting
---------------

See online issues on the [module issues] page on GitHub.


License
-------

This module is published under the [CeCILL v2.1] licence, compatible with
[GNU/GPL] and approved by [FSF] and [OSI].

In consideration of access to the source code and the rights to copy, modify and
redistribute granted by the license, users are provided only with a limited
warranty and the software’s author, the holder of the economic rights, and the
successive licensors only have limited liability.

In this respect, the risks associated with loading, using, modifying and/or
developing or reproducing the software by the user are brought to the user’s
attention, given its Free Software status, which may make it complicated to use,
with the result that its use is reserved for developers and experienced
professionals having in-depth computer knowledge. Users are therefore encouraged
to load and test the suitability of the software as regards their requirements
in conditions enabling the security of their systems and/or data to be ensured
and, more generally, to use and operate it in the same conditions of security.
This Agreement may be freely reproduced and published, provided it is not
altered, and that no provisions are either added or removed herefrom.

The [ViewerJS] library is published under the [GNU AGPL] license.
The [WebODF] library is published under the [GNU AGPL] license.
The [pdf.js] library is published under the [Apache] license.


Contact
-------

See documentation on the [ViewerJS] library on its site.

Current maintainers of the module:
* Daniel Berthereau (see [Daniel-KM])


Copyright
---------

ViewerJS and WebODF libraries:

* Copyright KO GmbH, 2013-2017

Javascript library [pdf.js]:

* Copyright Mozilla, 2011-2017

Module ViewerJs for Omeka S:

* Copyright Daniel Berthereau, 2017


[ViewerJs]: https://github.com/Daniel-KM/Omeka-S-module-ViewerJs
[Omeka S]: https://omeka.org/s
[ViewerJS]: https://viewerjs.org
[pdf.js]: https://mozilla.github.io/pdf.js
[WebODF]: https://github.com/kogmbh/WebODF
[Document Foundation]: https://www.documentfoundation.org
[module issues]: https://github.com/Daniel-KM/Omeka-S-module-ViewerJs/issues
[CeCILL v2.1]: https://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[FSF]: https://www.fsf.org
[OSI]: http://opensource.org
[Apache]: https://github.com/mozilla/pdf.js/blob/master/LICENSE
[GNU AGPL]: https://www.gnu.org/licenses/agpl-3.0.html
[Daniel-KM]: https://github.com/Daniel-KM "Daniel Berthereau"
