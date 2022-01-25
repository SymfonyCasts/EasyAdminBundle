# Controlling the Dashboard Menu

There are two things that we can do from our `DashboardController`. The first is to
configure the dashboard itself, which is mostly just the title, menu links, and also
controlling the user menu. The second is that we can configure things that affect
the CRUD controllers. And we saw an example of that with `configureActions()` where
we globally added a `DETAIL` action to every index page.

That was an *aewsome* start, but let's look a bit more at ways that we can configure
the dashboard itself.

## Linking to the Frontend

The `configureMenuItems()` method, as we already know, can link to a dashboard and
another CRUD section. Now let's add a link to the homepage. Say
`yield MenuItem::linkToRoute()`, passing "Homepage", an icon... and then
the route name and *optionally* route parameters. The name of our homepage route
is `app_homepage`.

Cool. Head over, refresh and... yea! We now have a nice homepage link on every page.
And if we click it, it works!

## linkToRoute() vs linkToUrl()

But whoa... check out the URL! That does *not* look like the homepage. The URL starts
with `/admin` and then has a bunch of query parameters. Yup, iit's rendering our
homepage controller *through* the admin dashboard... much like how our CRUD controllers
render through the dashboard. This works, but it's *not* what we intended.

So let's try again. The `linkToRoute()` method really means:

> Link to somewhere, but run that link through the admin section.

This can be useful if you have a custom controller but want to leverage some of the
EasyAdmin tools from inside that controller. If you just want to link to a page,
use `linkToUrl()` instead. This will have the same label, icon,m but then instead
of passing the route name, say `$this->generateUrl()` and pass `app_homepage`.

Go back to the admin page, refresh... click on page and... perfect! That looks much
better!

## Linking to the Admin from the Frontend

But what about a link *back* to the admin page, like up here in the header?
For that, open `templates/base.html.twig` and scroll down to the navbar... here
it is.

There's nothing here yet. Add `<li class="navbar-nav></li>` and a few
other classes. Inside, add an `<a>` with `href=""` set tio `path()`. To link
to the admin section, there's nothing special. Our `DashboardController` has a
*real* route, and its name is `admin`. So we can just link to that: `admin`. Give
our anchor a couple of classes to make it look good: `nav-link`... and I'll say
"Admin".

And we really only want to render this link if we have `ROLE_ADMIN`. So `{% if
isGranted('ROLE_ADMIN') %}` and then `{% endif %}` on the other side.

Beautiful! Let's give it a try. Refresh... there's the link and... we're right back
on our admin section!

## Customizing the User Menu

One other thing that the dashboard controls is this nice little user menu up here.
It shows who you're logged in as, an avatar that doesn't work yet, and a "Sign out"
link. In our system, users actually *do* have avatars on the frontend. You can see
this: this is an avatar for a user... and my user's avatar shows up in the upper
right. *But* EasyAdmin doesn't know that our users have avatars yet. We need to tell
it.

Back in `DashboardController`... it doesn't matter where... go to "Code Generate"
or "cmd + N" on a Mac, click "Override Methods", and select `configureUserMenu()`.
This has several methods on it. We can add other menu items (we'll do that in second),
set the avatar URL, and a few other things. I'll say `setAvatarUrl()` and pass it
`$user->getAvatarUrl()`: this is a custom method on my `User` class.

Notice that I'm not getting auto-completion on the method. That's because PhpStorm
doesn't know that this is our *custom* `User` class. So if you want to code defensively,
you can add `if (!user instanceof User)`, and `throw new \Exception('Wrong user.');`.
That won't ever happen, but now if I retype `$user->getAvatarUrl()`... that fixes
it!

And when we refresh... perfect! We have an avatar!

## Adding a Link to the User MEnu

The last thing I want to have is a link on the user menu that goes to my profile.
Earlier, we saw that another method you can call is `setMenuItems`... where you can
pass it in array of `MenuItem` objects. These items are the *same* ones that we've
been building in `configureMenuItems()`. So we can say, for example,
`MenuItem::linkToUrl` with `'MyProfile'`... some icons, and then
`$this->generateUrl()`. And the name to the route for my profile page is
`app_profile_show`.

That's it! Refresh and... new line! click and... that works too!

So there's nothing *too* complicated here: we can very easily to custom control of
*all* of the menus in the admin.

So next, let's talk about *assets* inside of EasyAdmin. This is how you can add custom
CSS and custom JavaScript to *any* section, including assets that are processed
through Webpack Encore.
