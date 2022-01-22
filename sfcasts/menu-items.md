# Menu Items

There are two things that we can do from our `DashboardController`. The first is to configure the dashboard itself, which is mostly just the title, menu links, and also controlling the use your menu. The second is that we can configure the things that affect the CRUD controllers, and we saw an example of that with `configureActions`, where we globally added a `DETAIL` action to every single index page. Let's look a bit more at ways we can configure the dashboard itself.

`configureMenuItems`, as we already know, can link to a dashboard and another CRUD section. Let's add a link to the homepage.

Here, I'll say `yield MenuItem::linkToRoute()`. We'll give it a route, `Homepage`, an icon, and then the route name and *optionally* route parameters. The name of my homepage route is `app_homepage`.

Go over and refresh...

We now have nice homepage link on every single part of dashboard! And if we click it, it works! But whoa... Check out the URL here. That does *not* look like a homepage. It's `/admin`. Then it has a bunch of query parameters. It's rendering our homepage controller through the admin area, which works, but it's *not* what we intended.

So let's try again. `linkToRoute` really means "link to somewhere, but run that through the admin section." This can be useful if you have a custom controller, but you actually want to leverage some of the EasyAdmin tools from inside that controller. If you just want to link to a page, we can use `linkToUrl`. It has the same label icon. And then here, instead of passing the route name, we'll just say `$this->generateUrl` and pass that `app_homepage`.

Go back to the admin page, refresh... click on page and... perfect! This looks much better. But what about a link *back* to the admin page, like up here in the header? For that, let's go to `templates/base.html.twig`. Scroll down to the navbar. Here it is.

There's nothing inside of it right now. I'm going to add `<li class="navbar-nav></li>` and a few other classes. And inside, add `<a>` that will `linkToPath`. And the, to link to the admin section, there's nothing special. Our `DashboardController` has a real route, and its name is `admin`, so we can just link to that admin route. Give our anchor a couple of classes to make it look good: `nav-link`... and I'll say "Admin" here.

We really only want to render this link if we have `ROLE_ADMIN`. So `{% if isGranted('ROLE_ADMIN') %}` and then `{% endif %}` on the other side of this.

Beautiful! Let's give that a try. Refresh... There's the link and... we're right back on our admin section.

One other thing that the dashboard controls is this nice little user menu up here. It shows who you're logged in as, an avatar that doesn't work yet, and a "Sign out" link. In our system, users actually *do* have avatars on the front end. You can see this is an avatar for a user, and that's an avatar for a user up there. *But* EasyAdmin doesn't know that our users have avatars yet. We need to tell it.

Back in our `DashboardController`... it doesn't matter where... I'm going to go to "Code Generate" or "cmd + N" on a Mac, go to "OverrideMethods", and select `configureUserMenu`. This has several methods on it. As you can see, we can add other menu items (we'll do that in second), set the avatar URL, and a few other things. I'll say `setAvatarUrl`.

Then, say `$user->getAvatarUrl`. This is a custom method on my user class, so you'll notice I'm not getting auto complete on that. That's because PhpStorm doesn't know that this is our custom user class. So if you want to code defensively, you can add `if (!user instanceof User)`, and `throw new \exception( 'Wrong user.');`. That won't ever happen, but now if I retype this... `userAvatarUrl`... that fixes it. Now, when we refresh... perfect! We have our avatar up there!

The last thing I would like to have is a little link down here that goes to my profile. Earlier, we saw that another method you can call is `setMenuItems`, where you can pass it in array of menu items. These items are the same ones we've been building down here. So we can say, for example, `MenuItem::linkToUrl` with `'MyProfile'`... and give this some icons.

Then I'll say `$this->generateUrl`... and the name to my route for my profile page is `app_profile_show`. That's it! I don't need any other argument. Refresh and... that works! Click on "My Profile" and... that works too!

There's nothing *too* complicated there, so we can very easily start to take custom control of all of our menu stuff in the admin.

Next, let's talk about assets inside of EasyAdmin. This is how you can add custom CSS and custom JavaScript to *any* section you want, including assets that are processed through Webpack Encore.
