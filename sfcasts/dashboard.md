# Dashboard

Coming soon...

If I run, get status, you can see that installing easy admin didn't do anything
fancy. It doesn't have a recipe that has config files or anything else. Just add it
itself and registered its bundle automatically. So simply installing the bundle.
Didn't give us any new outs or anything else special. For example, if I try to go to
/admin, hoping that it's gonna work it doesn't route not found that's because the
first step after installing easy admin bundle is to create an admin dashboard, a sort
of landing page for your admin. You'll typically have only one of these in your app
to get that going back at your terminal run Symfony console, make admin dashboard. As
a reminder, Symfony console is comp is exactly the same as running bin console. The
only difference is that running Symfony console allows the Docker environment
variables to be injected into this command. It makes no difference unless it actually
makes no difference unless you're running a command that requires database access. So
in this guy's bin console will work just fine. I'll Symfony console throughout this
tutorial. So Symfony console make admin dashboard we'll call our dashboard
controller. Sounds good to generate it and do source controller, admin and done. This
creates one new file for us. Source controller, admin dashboard controller dot PHP.
We'll let's go check it out.

Ooh. So let me see. There's not a lot here, but one thing you notice is that it has a
route for /admin. So now if I go over here to /admin, we do hit the admin dashboard.
Okay. So a few things to notice here first is that we do have a /admin route. This is
nothing fancy. There's nothing easy admin about this. This is just how we create
routes and Symfony. This is a pH B eight attribute route, which you may or may not be
familiar with. I've typically used pH annotations until now, but now that I'm using
pH B eight, I'll be using, uh, attributes instead of annotations, but they work
exactly the same. If you're still using pH B seven, then you can use, uh, annotations
just fine.

Now the dashboard controller is just a normal controller, though. It does extend an
abstracted dashboard controller. I'm gonna hold command and open that. And this
implements a dashboard controller interface. So this is a controller, but by
implementing the interface, it gives it special superpowers. And then short, there
are a number of methods inside of here that we can override to configure what our
dashboard looks like. And we're gonna be doing that little by little throughout this
tutorial now, because this is just a normal route in controller, and there's really
nothing special about it. It's not automatically secured. There's no security being
applied to this. I mean, check it out. I'm not even logged in right now. And I am
successfully on my admin dashboard.

So let's secure it. I'm gonna do this also with annotations. I have Senso framework,
extra bundle installed. So I can say is granted tab to auto complete that. And then
we will look for role_admin. That's kind of my base admin role. All admin users have
that role. Now when I refresh beautiful or bounced back over to the login page. So to
log in, if you open source data, fix at fixtures, I have a bunch of dummy users in
the database. There is a super admin, kind of a normal admin, and then somebody known
as a moderator. We'll talk more about those later, when we get deeper into how to
secure different parts of your admin with different roles, I'm gonna log in with
admin and example.com, password admin password, and beautiful. Now we're back to our
dashboard.

And of course, if you want to, instead of securing with, is grant with the, with the
PHP attribute, you can use this->deny acts, unless granted you can also go to config
packages, secure that AML and down at the bottom, you can always just use access
control for this whichever one you like better. So our dashboard is the jumping off
point for our admin, but there's nothing really too special here. Um, this page has a
title, uh, some menu items, a nice little user menu over here, and then eventually
we'll be able to actually generate something on something cool on this page, like
some links or some graphs or something, by the way, styling is all done in bootstrap
five with font. Awesome.

So let's see if we can customize the dashboard a little bit. Probably the best thing
about easy admin is that all the config is done in PHP, usually via methods in your
controller. So for example, wanna configure the dashboard. There is a configured
dashboard method for that. So we can change this, the title of the page two call
during overflow admin. And I wanna refresh, we get Coran overflow admin, and there
are a number of other methods. You can just look at the, um, uh, auto complete here.
So, uh, related to the fab icon path, there's various ways that you can modify
things. You can see there's things about the sidebar being minimized. That's
referring to a nice little feature here where you can double click on this little
thing to expand or, uh, collapse or expand the sidebar.

But really the main part of the dashboard are these menu items over here, which we
only have one right now, and this is controlled by configure menu items. So just to
show that we can let's change this FA to FA dashboard. Now these are font awesome.
This leverages the font awesome library. So FA dashboard corresponds to that little
icon right there. All right. So we can definitely do more with our dashboard, but
that's enough for now because what we're really here for are the crud controllers.
These are these sections of our site that it's going to allow us to create, read,
update, and delete all of our entities. Let's get those going next.

