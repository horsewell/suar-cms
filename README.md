Suar CMS
===================

Suar (sue-r) Content Management System is a basic text based web site management system. This is to fill the need for sites that don't have too much content, are updated via a client and don't need a lot of the features of a bigger CMS system. Suar is a Thai word that means Tiger and this CMS aims to be small, lean and powerful.

Written in PHP you will easily be able to extend it by adding plugin modules or templates.

This was based on a text based cms called lilcms, however, I have done a lot of changes. I needed a CMS to put on a system where I could not hook into the database and I needed to create it in a neutral language that can be installed on any server.

I found a script that manages .htaccess files to access the admin on so that there can be some basic user management.

Basically, the web designer / developer needs to set up the pages and create all the dynamic parts as PHP includes.

Now the template and the page content can use tokens that can be replaced dynamically via the admin. The templates also have sections that you will eventually be able to add things into. The templates can be chosen on a page to page basis. The each page stores all the data that belongs to it.

The web site content manager will be able to login and update the content. Currently, there is no upload or file browsing, it's only for text updates. I'm using the ckEditor but that could be swapped out for whatever editor that you like.

I plan on adding support for:

* caching
* Subset of markdown for text fields
* plugins to allow extra: metadata, tokens and template sections
* image and other file uploads

If you want to help me, a feature you want drop me a line. If you use this code it would be great if you could notify me. :)

Kind Regards,

Tyson
