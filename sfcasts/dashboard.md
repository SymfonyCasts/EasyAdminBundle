# Admin Dashboard

Run:

```terminal
git status
```

Installing EasyAdmin didn't do anything fancy: it doesn't have a recipe that adds
config files or a button that makes cute kittens appear. Darn. It just added itself
and registered its bundle. So simply installing the bundle didn't give us any
new routes or pages.

For example, if I try to go to `/admin`, we see "Route Not Found." That's because
the first step after installing EasyAdmin is to create an admin dashboard: a sort
of "landing page" for your admin. You'll typically have only one of these in your
app, but you *can* have multiple, like for different admin user types.

And we don't even need to create this dashboard thingy by hand! Back at your
terminal, run:

```terminal
symfony console make:admin:dashboard
```

As a reminder, `symfony console` is exactly the same as running `php bin/console`.
The only difference is that running `symfony console` allows the Docker environment
variables to be injected into this command. It typically makes no difference unless
you're running a command that requires database access. So, in this case,
`php bin/console` would work just fine.

I'll stick with `symfony console` throughout this tutorial. So say:

```terminal
symfony console make:admin:dashboard
```

We'll call it `DashboardController`, generate it into `src/Controller/Admin` and...
done! This created one new file: `src/Controller/Admin/DashboardController.php`.
Let's go check it out!

When I open it...

[[[ code('7cdbf2c4a2') ]]]

Hmm. There's not much here yet. But one thing you might notice is that it has
a *route* for `/admin`:

[[[ code('4e607c09de') ]]]

So now, if we find our browser and go to `/admin`... we *do* hit the admin dashboard!

***TIP
Since version `4.0.3` of EasyAdmin, this welcome page looks a bit different! For
example, it won't have the side menu that you see in the video. To see the links -
and follow better with the tutorial - create a new dashboard template that will
extend the base layout from EasyAdmin:

```twig
{# templates/admin/index.html.twig #}

{% extends '@EasyAdmin/page/content.html.twig' %}
```

Then, comment out the `return parent::index();` line in
`DashboardController::index()` and instead render this template:

```php
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
```

We'll talk much more later about how to use and design this dashboard page!
***

I want to point out a few important things. The first is that we *do* have a
`/admin` route... and there's nothing fancy or "EasyAdmin" about it. This is just...
how we create routes in Symfony. This is a PHP 8 *attribute* route, which you may
or may not be familiar with. I've typically used annotations until now. But because
I'm using PHP 8, I'll be using attributes instead of annotations throughout the
tutorial. Don't worry though! They work exactly the same. If you're still using
PHP 7, you can use annotations just fine.

The second important thing is that `DashboardController` is just a normal controller.
Though, it *does* extend `AbstractDashboardController`:

[[[ code('73a7f3ca33') ]]]

Hold `Cmd` or `Ctrl` and click to jump into that class.

This implements `DashboardControllerInterface`. So this *is* a normal controller,
but by implementing this interface, EasyAdmin knows that we're inside the admin
section... and boots up its engine. We'll learn *all* about what that means throughout
the tutorial.

*Most* importantly, this class has a number of methods that we can override to
configure what our dashboard looks like. We'll *also* be doing *that* throughout
this tutorial.

## Securing the Dashboard

And because this is just a normal route and controller, it *also* follows the
normal security rules that we would expect. Right now, this means that *no* security
is being applied. I mean, check it out: I'm not even logged in, but I *am*
successfully on the admin dashboard!

***TIP
In Symfony 6.2, you can use the `#[IsGranted()]` attribute without installing
SensioFrameworkExtraBundle. It's now part of the core!
***

So let's secure it! I'll also do this with an attribute. I already have
SensioFrameworkExtraBundle installed, so I can say `#[IsGranted()]` and hit "tab" to
auto-complete that. Let's require any user accessing this controller to have
`ROLE_ADMIN`... that's kind of a base admin role that all admin users have in
my app:

[[[ code('41cffae953') ]]]

Now when we refresh... beautiful! We bounced back over to the login page!

To log in, open `src/DataFixtures/AppFixtures.php`:

[[[ code('9b57929611') ]]]

I have a bunch of dummy users in the database: there's a super admin, a normal admin,
and then somebody known as a moderator. We'll talk more about these later when we
get deeper into how to secure different parts of your admin for different roles.

Anyways, log in with `admin@example.com`... password `adminpass`, and... beautiful!
We're back to our dashboard!

Of course, if you want to, instead of using the `IsGranted` PHP attribute, you
could also say `$this->denyAccessUnlessGranted()`. And you could *also* go to
`config/packages/security.yaml` and, down at the bottom, add an `access_control`
that protects the entire `/admin` section:

[[[ code('c9d5ab33fe') ]]]

Actually, adding this `access_control` is basically *required*: using only the
`IsGranted` attribute is *not* enough. We'll learn why a bit later.

## Configuring the Dashboard

So our dashboard is the "jumping off point" for our admin, but there's nothing
particularly special here. The page has a title, some menu items, and a nice little
user menu over here. Eventually, we'll render something cool on this page - like
some stats and graphs - instead of this message from EasyAdmin. Oh, and all of
this styling is done with Bootstrap 5 and FontAwesome. More on tweaking the design
later.

Before we move on, let's see if we can customize the dashboard a little bit. One
of the absolute *best* things about EasyAdmin is that all the config is done in PHP.
Yay! It's usually done via methods in your controller. For example: want to configure
the dashboard? There's a `configureDashboard()` method for that!

[[[ code('f40ac1071e') ]]]

We can change the title of the page to "Cauldron Overflow Admin":

[[[ code('dd090553fe') ]]]

When we refresh... we see "Cauldron Overflow Admin"! And there are a number of other
methods... just look at the auto-complete from your editor. There are methods related
to the favicon path... and something about the sidebar being minimized. That's referring
to a nice feature where you can click on the separator for the sidebar to collapse
or expand it.

The *main* part of the dashboard is really these menu items. And, we only have one
right now. This is controlled by `configureMenuItems()`:

[[[ code('8e02f49f40') ]]]

Just to prove that we can, let's change the icon to `fa-dashboard`:

[[[ code('d78cb5f50a') ]]]

This leverages the FontAwesome library. When we refresh, new icon!

So we can *definitely* do more with our dashboard, but that's enough for now.
Because what we're *really* here for are the "CRUD controllers". These are the
sections of our site where we will be able to create, read, update, and delete all
of our entities. Let's get those going next!
