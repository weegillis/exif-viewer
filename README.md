exif-viewer
===========

A program to extract and view EXIF data stored in photo headers

**css**, **includes** and **templates** are site root mapped.

**tools** is not necessary, per se, as a folder. The contents, quite the reverse.

**exif** can be renamed to any image folder.

Once the root files are in place, and the paths set accordingly in the folder files, the URL can be requested. All images in the folder will be discovered and their EXIF header read in. The index page is the Image Navigator drawing thumbnails from the header where available, else rendering the image as a thumbnail.

Click a thumbnail to open the viewer on that image. Width is responsive but height is not, at present. View page source to inspect the EXIF dump that is hidden in a DOM element called 'dump'. GPS data is mapped to Wikimapia.org. 

Can be veiwed at http://my.tenfingers.net/tools/exif/

More can be said about the usage. To be clear, so long as the library is centrally located, along with the template folder, the three HTML pages, index.php, viewer.php and thumbnail.php can be mounted into any folder that contains image files. Will need to go into the code to see what formats are discoverd, but it's very likely confined to JPEG and PNG since it is a photo navigator.

There is no code to edit when images are added to the folder. To be clear, there is never any need to edit the code. One might edit the image Comment in their photo editor (we use GIMP), and possibly scale or otherwise filter, etc. the image locally. The image is simply mounted on the host site by uploading to a folder that contains the above three files. Refresh the navigator page and the image will be there as a thumbnail with attendant header information as applies, or is available. 

If there is a GPS array in the header *1, it will be parsed and translated to a Wikimapia.org URL, in addition to being written to the photo description.

From what memory serves, the navigator can be written into an existing page template as a required object. (Sorry, that site is now down.) In other words it can be dropped into any page layout without modification. Attribution goes without saying, otherwise this is a free license.

Unless one has PHP locally, this cannot be implemented as a local standalone. One would be interested to see any ported versions in Node.js. Tag me in if this is ever forked for that purpose. A lot more users would have access to this feature if they could utilize it on their own machine to see what information is stored by their camera.

1. Some image editors permit the insertion of a GPS data array into the image header. It does require a special format which one would need to compute from the lat, lng attributes of the geolocation. Altitude information is also part of the array data, again with the need to compute into special format. Bottom line, given the right application, one can add GPS data to photo headers. That is something we could use as a standalone application; hint, hint.
