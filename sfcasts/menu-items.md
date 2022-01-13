# Menu Items

Coming soon...

There are two things that we can do from our dashboard controller. The first is to
configure the dashboard itself, which is mostly just like title, menu links, and also
controlling the use your menu up here. The second is that we can configure the things
that affect the crud controllers. And we saw an example of that with configure
actions, where we globally added a detail action to every single index page. Let's
look a bit more at ways we can configure the dashboard itself. So configure menu
items, we already know we can link to a dashboard and how we can link to another CR
how we can link to a crub section. Let's add a link to the homepage. So on here, I'll
say yield Menu, item, link to route. How about link you route that looks right and a
here we'll put a label. So I'll say homepage, an icon, and then the route name and
optionally route parameters. The name of my homepage route is app_homepage. All
right, so we go over and refresh. We now have nice homepage link on every single part
of dashboard. And if we click it, it works, but whoa, check out the URL here. That
is, that does not look like a homepage it's /admin.

And then it has a bunch of query parameters. It's rendering our homepage controller
through the admin area, which works, but it's not what we intended. So let's try
again. Instead of link to route link, route really means link to link somewhere, but
run that link somewhere, but run that through the admin section. This can be useful
if you have a custom controller, but you actually wanna leverage some of the easy
admin tools from inside that controller. If you just wanna link to a page, we can use
link to URL and it has the same label icon. And then here instead of passing the
brown name, we'll just say this->generate URL and pass that app under homepage. Now
go back to admin refresh, click on page. Perfect. Much better. What about a link back
to the admin, like up here in the header? Well, for that says nothing. Let's go to
templates based at H amount twig Scroll down to a nav bar. Here it is. There's
nothing inside of it right now. And I'm gonna add ally Class = nav bar dash nav, and
a few other classes here And inside and a that will link to path. And then to link to
the admin section. There's no thing special. Our dashboard controller has a real
route. Its name is admin, so we can just link to that admin route. So we give our
anchor a couple classes to make it look good, a dash link, and I'll say admin inside
of here. And let's see the of them missing is we really only wanna run to this link
if we have role admin. So if is granted

Roll under square admin and then end if on the other side of this

Beautiful, let's give that a try refresh there's the link and right back on our admin
section. So one, one other thing that dashboard controls this nice little user menu
up here shows who you're logged in as an avatar that doesn't work yet, and a sign out
link. Let's make sure that in our system, our users actually do have avatars on the
front end. You can actually see, this is an avatar for a user, and that's an avatar
for the user up there, but, but easy admin doesn't know that our users have avatars
yet. We need to tell it. So back in our dashboard controller, doesn't matter where
I'm gonna go to code generate or command N on a Mac, go to override methods, an
override config, your user menu. And then this has several methods on it. As you can
see, we can add other menu items. We'll do that in second set, avatar URL, and a few
other things I'm gonna say set, avatar URL.

And then I'm saying user arrow, get avatar. U I no. So I'm not getting, this is a
custom method on my user class. You notice I'm not getting auto complete on that.
It's just because <inaudible> doesn't know that this is our custom user class. If you
wanna code defensively, you can add if not user instance of our user class, then
throw new exception, wrong user that won't ever happen. But now on here, if I kind of
retype this, get user avatar URL, actually there you go. I actually type, I had URL
last time. It's actually URL. So that fixes it. It's now gonna refresh perfect. We
have our avatar up there. And the last thing I would like to have is a little link
down here that links to my, my out. So we already saw a second ago that another
method you can call is set menu items where you can pass it in array of menu items.
These menu items are gonna be the same menu item, things that we've been building
down here. So we can say, for example, menu item, link to URL, I'll say my profile,
give this some icons.

And then I'll say this error generate URL. And then the name to my route for my
profile page is add at profile show. And that's it. I don't need any other argument
on that and refresh that works. Click on my profile. We've got it. So nothing too
complicated there, but we can very easily kind of start to take custom control all
the, all of our menu stuff in the admin. So next let's talk about assets inside of
easy admin. This is the way that you can add a custom CSS and custom JavaScript to
any section that you want. Even including assets that are processed through Webpac
Encore.

