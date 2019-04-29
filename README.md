exif-viewer
===========

A program to extract and view EXIF data stored in photo headers

**css**, **includes** and **templates** are site root mapped.

**tools** is not necessary.

**exif** can be renamed to any image folder.

Once the root files are in place, and the paths set accordingly in the folder files, the URL can be requested. All images in the folder will be discovered and their EXIF header read in. THe index page is the Image Navigator drawing thumbnails from the header where available, else rendering the image as a thumbnail.

Click a thumbnail to open the viewer on that image. Width is responsive but height is not, at present. View page source to inspect the EXIF dump that is hidden in a DOM element called 'dump'. GPS data is mapped to Wikimapia.org. 
