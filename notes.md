TODO
====

# Backend
- Finish image upload
- Create thumbnailing system
- Decide on gallery management system. Please think this through. (Design)
    - Should we use the gallery, or list style? What is best? Maybe ask her?

# Frontend
- Make it (lol)
- Design first, this time

Najas Structure
===============

# Pages
A **Page** can be anything, more or less. Just a route, basically.

The pages are
- Portfolio
  - Contains projects
- About
- *Admin*
- (Blog)?


# Projects
A **Project** describes a project done by the illustrator. It can contain various fields.
Most important is the **Gallery**

- Project
  - Author
  - Period
  - Description
  - **Short** description (teaser)
  - **Gallery**
  - Thumb?
  - Other meta-data

## Project stucture for site
- Illustration Work
  - Gallery
- Work in Volume
  - Gallery
- Miscellaneous
  - Gallery

# Gallery
A gallery is a collection of images.
The appearance is very important.
The images should be easily navigatable and viewable. Zoomable as well, ofc.

# Image
Images should stored as files. Galleries can contain images. Several galleries can contain the same image.

# Admin
The Admin interface must allow
- Pages CRUD
- Projects CRUD
- Gallery CRUD
- Images CRUD
- Connecting pages/projects/galleries
- General customization of site (name/owner/etc)
- User administration (mostly for me)

# TODO
- Create database design
- Create models
- Find libraries
- Create page load roadmap
- Create API structure
- Create UX

# DB structure
- Tables
  - projects
  - images
  - galleries
  - images_galleries
- Relations
  - A **project** can have multiple galleries
  - A **gallery** can have multiple images
  - An **image** can belong to several galleries
  - A **gallery** can only belong to one project
# Use Cases


## Create a new project
## Create a new gallery
## Assign a gallery to a project
## Upload an image(s)
## Add image(s) to a gallery
## Remove images from a gallery

