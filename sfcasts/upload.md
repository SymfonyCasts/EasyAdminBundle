# Upload Fields

Our `User` class also has a property called `$avatar`. In the database, this
stores a simple filename, like `avatar.png`. Then, thanks to a `getAvatarUrl()`
method that I created before the tutorial, you can get the full URL to the image,
which is `/uploads/avatars/["the file name"]`. To get this to work, if you create a
form that has an upload field, we need to *move* the uploaded file *into* this
`public/uploads/avatars/` directory and then store whatever the filename is onto
the `avatar` property.

Let's add this to *our* admin area as an "Upload" field and... see if we can get
it all working. Fortunately, EasyAdmin makes this pretty easy! It's like it's
in the name or something...

## The ImageField

Back over in `UserCrudController` (it doesn't matter where, you can have this
in whatever order you want), I'm going to say `yield ImageField::new('avatar')`.
If you have an upload field that is *not* an image, there isn't a generic
`FileField` or anything like that. But you *could* use a `TextField`, then
override its form type to be a special `FileUploadType` that comes from
EasyAdmin. Check the `ImageField` to see what it does internally for more details.

*Anyways*, let's see what this does. Head back to the user index page and... ah!
Broken image tags! But they *shouldn't* be broken: those image files *do* exist!

## Setting the Base Path

Inspect element on an image. Ah: *every* image tag literally has just `/`
then the filename. It's missing the `/uploads/avatars/` part! To configure that,
we need to call `->setBasePath()` and pass `uploads/avatars` so it knows were to
look. If you're storing images on a CDN, you can put the full URL to your
CDN right here instead. Basically, put whatever path needs to come right *before*
the actual filename.

## Setting the Upload Dir

Head back over, refresh and... got it! Now edit the user and... error!

> The "avatar" image field must define the directory where the images are uploaded
> using the `setUploadDir()` method.

That's a pretty great error message! According to this, we need to tell the
`ImageField()` that when we upload, we want to store the files in the
`public/uploads/avatar` directory. We can do that by saying `->setUploadDir()` with
`public/avatars/uploads`. Um, actually that path isn't quite right.

And when I refresh... EasyAdmin tells me! The directory *actually* is
`public/uploads/avatars`. Now that I've fixed that... it works. And that's nice!

The field renders as an upload field, but with a "delete" link, the current filename
and even its size! Click the file icon and choose a new image. I'll choose my friend
Molly! Hit save and... *another* error.

> You cannot guess the extension as the Mime component is not installed. Try running
> "composer require symfony/mime".

The Mime component helps Symfony look inside of a file to make sure it's *really*
an image... or whatever type of file you're expecting. So, head over to your terminal
and run:

```terminal
composer require symfony/mime
```

Once that finishes, spin back over, hit refresh to resubmit the form and... yes!
There's Molly! She's adorable! And if you look over in our `public/uploads/avatars/`
directory, *there's* the file! It has the *same* filename as it did on my computer.

## Tweaking the Uploaded Filename

That's... not actually perfect... because if someone *else* uploaded an image
with the same name - some other fan of Molly - it would *replace* mine! So let's
control how this file is named to avoid any mishaps.

Do that by calling `->setUploadedFileNamePattern()`. Before I put anything here,
hold "cmd" or "ctrl" to open that up... because this method has *really* nice
documentation. There are a bunch of wildcards that we can use to get *just* the
filename we want. For example, I'll pass `[slug]-[timestamp].[extension]`, where
`[slug]` is, sort of a cleaned-up version of the original filename. By including
the time it was uploaded, that will keep things unique!

Ok, edit that same user again, re-upload "Molly", hit save and... beautiful! It
*still* works! And over in the file location... awesome! We now have a "slugified"
version of the new file, the timestamp, then `.jpg`. And notice that the old file
is gone! That's another nice feature of EasyAdmin. When we uploaded the new
file, it deleted the original since we're not using it anymore. I love that!

## Handling Non-Local Files & FileUploadType

Oh, and many people like to upload their files to something like Amazon S3 instead
of uploading them locally to the server. Does EasyAdmin support that? Totally! Though,
you'll need to hook parts of this up by yourself. Hold "cmd" or "ctrl" to open
`ImageField`. Behind the scenes, its form type is something called
`FileUploadType`. Hold "cmd" or "ctrl" *again* to jump into that.

This is a custom EasyAdmin form type for uploading. Scroll down a bit to find
`configureOptions()`. This declares all of the options that *we* can pass to this
form type. Notice there's a variable called `$uploadNew`, which is set to a callback
and `$uploadDelete`, which is *also* set to a callback. Down here, these become
the `upload_new` and `upload_delete` options: two of the *many* options that you
can see described here.

So if you needed to do something *completely* custom when a file is uploaded - like
moving it to S3 - you could call `->setFormTypeOption()` and pass `upload_new`
set to a callback that contains that logic.

So it's *very* flexible. And if you dig into the source a bit, you'll be able
to figure out exactly what you need to do.

Next, it's time to learn about the purpose of the "formatted value" for each field
and how to control it. That will let us render *anything* we want on the index
and detail page for each field.
