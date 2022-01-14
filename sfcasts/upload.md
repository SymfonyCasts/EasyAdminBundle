# Upload

Coming soon...

Our user class also has a property called avatar in the database. This just stores a
simple file name, like avatar dot P and G. Then thanks to a, a get avatar, your
method that I created before this tutorial, you can get the full URL to that avatar,
which is /upload /avatar /the file name. So to get this to work, if you create a form
that has an upload field, you need to actually move the upload of files into this
public uploads, avatars directory, and then store whatever the file name is onto this
avatar field. I wanna add this to our admin area as an upload field and get that
working.

It turns out easy. Admin makes this pretty easy smack over an user crowd controller.
Doesn't matter where you can do whatever order you want. I'm going to yield a new
image field, Ooh, image, field new, and then call it avatar. Let's start right there.
So head back to the users homepage and you can see it's immediately broken. It
shouldn't be broken. Those files are there. If you inspect element, you'll see that
every image tag literally has just /and then the file name it's missing the /upload
/avatars part to configure that we just need to call->set base path. So quite
literally here, we'll put uploads /avatars.

And if you restoring your images on some CDN, you could put the full URL to your CDN
right here, basically whatever comes right before the actual file name. So now
refresh. Got it. All right. So let's go at a user here. And in another error, an
error, the avatar image field must define the directory where the images are uploaded
using the set upload D method. That's a pretty great error message. So we need to
tell that when we upload it, we want it to go into public uploads, avatar. So you can
do that by saying set uploader, and then very little like public /avatars /uploads.
Now we refresh, Oh, another error because you probably saw it. I totally step my
order here. Public /uploads then /avatars. So the, for the field has my back. Let's
check to make sure I don't do something crazy. Sweet. And now we have this great
little menu here, including even a delete link. All right. So lemme choose a file
here. We'll choose Molly. <affirmative> hit save. And another error. You cannot guess
the extension as the mind component is not installed. So it says trial running
composer requires so many /mine. Come on in, dude. Hey,

Huggy made it home with you. Can I?

So this is the library that just helps Symfony look inside of a file to make sure
it's an image or some other type of file. So I'll go mark command line, Ron composer,
Symfony /mime. And once that finishes SIM back over, I'll hit refresh to resubmit the
form. And yes, there we are. There's Molly. We've got it. And it works. Cause if you
look over in our public uploads, avatars directory, there is the file. You can see it
literally has the same file name as when we upload it, Which actually isn't perfect
because you could have, you could have, if somebody else uploads an image with the
same name, they would, it would replace mine. So we wanna control a little bit of how
this file is actually called inside the directory.

You can do that by calling set uploaded file name pattern. And before I even put in
anything here, I'm gonna hold command or control to open that up. This has really
good documentation. There are a bunch of wild that you can use inside of here to get
just the, um, file name you want. So for example, ill pass slug dash timestamp dot
extension, where slug is kind of a cleaned up version of the original file name
timestamp, and then the extension. So now go back, I'll go back in at the same user
I'll re-upload Molly hit save and beautiful. It still works. Check this out over
here. Awesome. You can see, we have kind of these SLU five version of the new file
timestamp than dot JPEG. It notice the old one was deleted. That's another nice
feature of this, of easy admin. When we replaced the original, it deleted when we
uploaded this new one, it deleted the old one since we weren't using it anymore. Oh,
and by the way,

If you are not uploading files locally to, to your server, some people instead of
doing that, they will upload them to something like Amazon S3 does easy admins for
that. Totally though. You'll need to hook this up hook parts of this up by yourself.
So a whole commander control and open image field, you can see behind the scenes, its
form type is something called file upload type hold, command, or control to jump into
that. This is a custom easy admin form type for uploading. And if you scroll down a
bit, you'll find configure options. This is configuring all the options that we can
pass to this form type and notice there's a, a variable here called upload new, which
is just set to a callback and upload delete, which is set to a callback. These both
com I go on here, upload_new and upload_delete options. So two of the many options
that you can see here. So if you needed to do something completely custom on whenever
you uploaded a file, you could call set custom, is that

Say, what is that way left?

Yes, You can call set form type options And pass upload new here. Actually it's set
form type option, upload new, and then you could pass some sort of call back here and
you could do whatever custom logic you wanted to. So very flexible. And if you're dig
into the source code a little bit, you're gonna be able to figure out exactly what
you need to do. Anyways. If we go back to the index page, you notice one of the cool
things about the image field is that you can click to see a bigger version of it, But
let's suppose that we don't need that, or don't want that since these are just
avatars, we just wanna have little tiny versions of them. There's actually a field
inside of easy admin. That's made specifically for avatars, it's called the avatar
type avatar field.

So I'm going to yield avatar, field new and pass it avatar. So, you know, I kind of
have like two fields for avatar temporarily. We see here, if I refresh this one
works, but this one is now broken. <affirmative> if you inspect image on this and
look at that, huh? Looks like the same problems before. It's just dumping out the
file name instead of the full path to it. So at the image field, there was a set base
path method. So let's see if there's a set base path method here, set base. There is
not. So one of the things, no matter which field type you use, when a field is
ultimately printed out on a page, what's printed is something called the formatted
value. And the formatted value is something you can control on any field. You can do
that by calling->format value and then passing this a callback. So I'm going to do a
static function. This is going to re receive the actual format of value, whatever
easy admin would normally use as a format of value, and then our NST. So user object,
And then here we will return whatever we want, the actual format of value to be. So
for us, we can actually say return user arrow, get avatar URL. Now real quick. This
is not important to see people put that it would work fine without it. Um, you can
put static as long as you don't need to use the, this variable inside of here. That's
not gonna work because we're inside of a static function

Anyways. Now, refresh. Perfect. It works a nice little avatar. Now the thing about
the avatar field is that if you want to, either, if you go to the form, it's kind of
an interesting thing here. There aren't two avatar fields. And the reason for that is
that even though we can display two avatar fields on the index page, we can't have
two avatar fields in the form. The second one always wins and that's fine. We don't
want two fields. I just wanted to point out that's why that's happening. If we did
the leak, the image field and use the avatar field on the form, you'd see that the
avatar field actually inside the form, isn't anything fancy. It's just a text field.
So ultimately what we want is we wanna use the image field on the form and the avatar
field on the list.

And, and we know how to do that down here. I'm gonna say only on forms and up here,
whoops, I'll say only I'll say hide on form. That gives us the exact result that we
want. Oh, by the way, one quick thing. Um, in this format, value callback,
technically this user here should be allowed to be no, we're gonna talk a about why
later, when we talk about entity permissions. So in a real project, I would actually
make this look like this. So that would allow user to be Knowle. And this is a new
syntax that basically says if user, if we actually have a user, then call this
method. If we don't user, then just return. No. So I'm actually gonna remove this for
now. We'll re add that later when we hit an air, but I wanna give you a little hint
right now about that. All right, next, let's customize more fields inside of more,
instead of more of our controllers, including leveraging and configuring the very
special and very powerful association field.

