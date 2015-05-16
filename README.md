Basic text file CMS
===================

A CMS based on Text files in written PHP

This Content Management System is based on lilcms, however, I have done a lot of changes. I needed a CMS to put on a system where I could not hook into the database and I needed to create it in a neutral language that can be installed on any server.

I found a script that manages .htaccess files to access the admin on apache (my environment) so that there can be some basic user management.

Basically, the web designer / developer needs to set up the pages and create all the dynamic parts as PHP includes.

The web site content manager will be able to login and update the content. Currently, there is no upload or file browsing, it's only for text updates.

We use ckeditor and I have included a config.js for it which can be changed depending on your requirements. This is not to say you couldn't use any text editor that is available today, if you can use it with a normal text-area.

I plan on adding support for:

* ~~variables, things like email addresses, phone numbers and other things that appear on the web site~~
* editing meta-data on pages (having standard tags and able to add extra tags when needed)
* image and other file uploads but this is dangerous as people can upload viruses, inappropriate files or super large files.

If you want to help me, think there is a feature you want or use this code it would be great if you could drop me a message too. :)

Kind Regards,

Tyson
