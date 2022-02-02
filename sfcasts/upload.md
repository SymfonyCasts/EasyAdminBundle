# Upload

Our user class also has a property called `$avatar`. In the database, this
just stores a simple file name, like `Avatar.png`. Then, thanks to a `getAvatarUrl()` method that I created before this tutorial, you can get the full URL to that avatar, which is `/upload/avatar/["the file name"]`. To get this to work, if you create a form that has an upload field, you need to actually *move* the upload of files into this `/public/uploads/avatars` directory, and then store whatever the file name is onto this `avatar` field. I want to add this to our admin area as an "Upload" field and get that working. As it turns out, EasyAdmin makes this pretty easy. It's like it's in the name or something...

 Back over on `UserCrudController.php`... (it doesn't matter where, you can have this in whatever order you want) I'm going to say `yield ImageField::new()` and then call it `avatar`. That's a good start. Head back to the users homepage and... you can see it's immediately broken. It *shouldn't* be broken, though. Those files are there.
 
 If you inspect the element, you'll see that *every* image tag literally has just `/` and then the file name. It's missing the `/upload/avatars` part! To configure that, we just need to call `->set BasePath()` and add `uploads/avatars` so it knows were to look. If you're storing your images on some CDN, you could put the full URL to your CDN right here instead. Basically, you just need whatever comes right before the actual file name.
 
 Head back over, refresh and... got it! All right. Let's go edit user here and... another error! What..?

 >The "avatar" image field must define the directory where the images are uploaded using the set-UploadDir() method.
 
 That's a pretty great error message. According to this, we need to tell our `ImageField()` method that when we upload it, we want it to look in `public/uploads/avatar`. You can do that by saying `->set UploadDir()` and then direct it to `public/avatars/uploads`. Refresh again, and... oh, another error. Rats...
 
You probably saw it. I totally messed up my order here. It needs to be `public/uploads/avatars`. Okay... now that I've fixed that, let's check to make sure it works.

Sweet! We have this great little menu here, which even includes a "delete" link. All right, I'll click on the little file icon here to choose an image file. I'll choose Molly. Hit save and... *another* error.

>You cannot guess the extension as the Mime component is not installed. Try running "composer require symfony/mime".

This is the library that just helps Symfony look inside of a file to make sure it's an image or other type of accepted file.

So, we'll head over to our terminal and run:

```terminal
composer require symfony/mime
```

Once that finishes, spin back over, hit refresh to resubmit the form and... yes! There's Molly! And if you look over in our `public/uploads/avatars` directory, *there's* the file. You can see it has the *same* file name as it did when we uploaded it. This isn't perfect, because if someone else uploaded an image with the same name, it would replace mine. So, we'll want to control how this file is called inside the directory to avoid any mishaps with our profile pictures. We can do that by calling `->setUploadedFileNamePattern()`. Before I put anything here, I'll hold "cmd" or "ctrl" to open that up.

This has really good documentation. There are a bunch of wildcards in here that you can use to get the file name you want. For example, I'll pass `[slug]-[timestamp].[extension]`, where `[slug]` is sort of a cleaned up version of the original file name, plus the time it was uploaded, and then the extension. Back on the "edit user" page (I went to the same user as before), I'll re-upload "Molly", hit save and... beautiful! It still works! And over in the file location... awesome! We now have a "slug-ified" version of the new file, the timestamp, then `.jpg`. Notice that the old file was deleted. That's another nice feature of EasyAdmin. When we uploaded the new one, it deleted the old one since we weren't using it anymore. Convenient!

Oh, and by the way, some people like to upload their files to something like Amazon S3 instead of uploading them locally. Does EasyAdmin support that? Totally! Though, you'll need to hook parts of this up by yourself. Hold "cmd" or "ctrl" to open `ImageField`. you can see behind the scenes, its `FormType()` is something called `FileUploadType::class`. Hold "cmd" or "ctrl" *again* to jump into that.

This is a custom EasyAdmin form type for uploading. And if you scroll down a bit, you'll find `configureOptions`. This is configuring all the options that we can pass to this form type. Notice there's a variable here called `$uploadNew`, which is just set to a callback, and `$uploadDelete`, which is also set to a callback. Down here, these both become `upload_new` and `upload_delete` options - two of the *many* options that you can see here.

If you needed to do something completely custom whenever you upload a file, you could call `->setFormTypeOption()` and pass `upload_new` here. Then you could pass some sort of call back here and you do whatever custom logic you wanted to. So it's *very* flexible. If you dig into the source code a little bit, you'll be able to figure out exactly what you need to do.

Head back to the index page. One of the cool things about the `ImageField` is that you can click to see a bigger version of it. But let's suppose that we *don't* need that, or don't want that. Since these are just avatars, we only want to have tiny versions of them. There's actually a field inside of EasyAdmin that's made specifically *for* avatars. It's called the `AvatarField`.

So, back over here, I'm going to say `yield AvatarField::new()` and pass it `avatar`. I'll have two fields for `avatar` temporarily. Go refresh and... *this* one works, but *this* one is now broken. If you "Inspect" the image... huh? This looks like the same problem as before. It's just dumping out the file name instead of the full path to it. At the `ImageField`, there was a `->setBasePath()` method. So let's see if there's a `->setBasePath()` method here and... there is *not*.

No matter which `FieldType` you use, when a field is ultimately printed out on a page, what's printed is something called the "formatted value". And the formatted value is something you can control on *any* field. You can do that by calling `->formatValue()` and then passing this a callback. I'm going to use a `static function()`. This is going to receive the actual formatted `$value` (whatever EasyAdmin would normally use as a formatted `$value`), and then our entity - so `User $user`. And in here, we'll return whatever we want the actual formatted `$value` to be. For us, we can say `return $user->getAvatarUrl()`.

This `static` is not important. You'll see people put that, but it will work fine without it. You can put `static` here as long as you don't need to use the `$this` variable inside of here. That's not going to work because we're inside of a `static` function.

Anyways, go back to your browser and refresh. Perfect! We have a nice little avatar! The thing about the `AvatarField` is that, if you go to the form, hm... interesting. There's only *one* `AvatarField` here. The reason for that is that even though we can display *two* avatar fields on the index page, we can't have two avatar fields in the form. The second one always wins and that's fine. We don't actually *want* two fields. It's just good to understand why that's happening. If we did delete the `ImageField` and use the `AvatarField` on the form, you'd see that the `AvatarField` inside the form isn't anything fancy. It's just a `TextField`. Ultimately, we want to use `ImageField` on the form and `AvatarField` on the list. And we already know how to do that!

Down here... I'll say `->onlyOnForms()` and up here, I'll say `->hideOnForm()`. This gives us the exact result we're looking for. Oh, I almost forgot! In this `->formatValue()` callback, technically this `User` here should be allowed to be null. We'll talk about *why* later on when we talk about entity permissions.

In a real project, I would actually make this look like this:

```
->formatValue(static function($value, ?User $user) {

    return $user?->getAvatarUrl();
})
```

 That would allow `User` to be null. This is a new syntax that basically says "If we actually have a user, then call this method. If we *don't* have a user, then just return null." I'm actually going to remove this for now and we'll re-add it later when we hit an error. I just wanted to give you a little hint about that right now.
 
 Next, let's customize more fields inside of our controllers, including leveraging and configuring the *very* special and very powerful `AssociationField`.
