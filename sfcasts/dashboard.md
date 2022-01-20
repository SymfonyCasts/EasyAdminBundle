# Dashboard

If I run

```terminal
git status
```

you can see that installing EasyAdmin didn't do anything fancy. It doesn't have a recipe that has config files, or a button that makes cute kittens appear. Darn... It just adds itself and registers its bundle automatically. So simply installing the bundle didn't give us any new outs.

For example, if I try to go to `/admin`, it gives us "Route Not Found." That's because the first step after installing the EasyAdmin bundle is to create an admin dashboard - a sort of landing page for your admin. You'll typically only have one of these in your app. 

To get that going, back at your terminal, run:

```terminal
symfony console make:admin:dashboard
```

As a reminder, `symfony console` is exactly the same as running `php bin/console`. The only difference is that running `symfony console` allows the Docker environment variables to be injected into this command. It generally makes no difference unless you're running a command that requires database access. So in this case, `php bin/console` would work just fine.

I'll stick with `symfony console` throughout this tutorial. So say:

```terminal
symfony console make:admin:dashboard
```

We'll call it "DashboardController."

Sounds good! To generate it, say `src/Controller/admin` and... done! This creates one new file for us: `src/Controller/Admin/DashboardController.php`.

Let's go check it out!

Ooh... Let me see... There's not a lot here, but one thing you might notice is that it has a route for `/admin`. So now if I go over here to `/admin`... we do hit the admin dashboard!

Okay, so there's a few things to notice here. First is that we do have a `/admin` route. This isn't fancy, and there's really nothing "EasyAdmin" about it. This is just how we create routes in Symfony. This is a PHP 8 attribute route, which you may or may not be familiar with. I've typically used annotations until now, but now that I'm using PHP 8, I'll be using attributes instead of annotations. Don't worry, though! They work exactly the same. If you're still using PHP 7, then you can use annotations just fine.

The `DashboardController` is just a normal controller, but it extends `AbstractDashboardController`. I'll hold "Cmd" and open that. 

This implements `DashboardControllerInterface`. So this is a controller, but by implementing the interface, it gives it special superpowers. There are a number of methods inside of here that we can override to configure what our dashboard looks like. We'll be doing that little by little throughout this tutorial. 

Because this is just a normal route in controller, there's really nothing special about it. It's not automatically secured and there's no security being applied to this. I mean, check it out: I'm not even logged in right now, and I am successfully on my admin dashboard.

So let's secure it! I'll also do this with annotations. I already have SensioFrameworkExtraBundle installed. So I can say `#[IsGranted()]` and hit "tab" to auto complete that. Now we'll look for `ROLE_ADMIN`. That's kind of my base admin role, so all admin users will have that role. Now when I refresh... Beautiful! We bounced back over to the login page!

So to log in, if you open `src/DataFixtures/AppFixtures`, I have a bunch of dummy users in the database. There is a super admin, a normal admin, and then somebody known as a moderator. We'll talk more about those later, when we get deeper into how to secure different parts of your admin with different roles.

I'll log in with "admin@example.com"... password "admin password"...

And... beautiful! Now we're back to our dashboard!

Of course, if you want to, instead of securing `IsGranted` with the PHP attribute, you can use `$this->denyAccessUnlessGranted()`. You can also go to `config/packages/security.yaml` and, down at the bottom, you can always just use access control for this - whichever one you like better.

So, our dashboard is the jumping off point for our admin, but there's nothing too special here. This page has a title, some menu items, and a nice little user menu over here. Eventually, we'll be able to actually generate something cool on this page, like links or graphs or something. By the way, styling is all done in Bootstrap 5 with FontAwesome.

So let's see if we can customize the dashboard a little bit. One of the best things about EasyAdmin is that all the config is done in PHP, usually via methods in your controller. So, for example, want to configure the dashboard? There is a configured dashboard method for that!

We can change the title of the page to call during overflow admin. And I wanna refresh, we get Coran overflow admin, and there are a number of other methods. You can just look at the auto complete here. So, related to the fab icon path, there's various ways that you can modify things. You can see there's things about the sidebar being minimized. That's referring to a nice little feature here where you can double click on this little thing to expand or collapse the sidebar.

The main part of the dashboard is really the menu items over here, which we only have one of right now. It's controlled by `ConfigureMenuItems`. So, just to show that we can, let's change this FA to `fa-dashboard.` This leverages the FontAwesome library. So `fa-dashboard` corresponds to that little icon right there.

All right! So we can definitely do more with our dashboard, but that's enough for now because what we're really here for are the CRUD controllers. These are the sections of our site where we'll be allowed to create, read, update, and delete all of our entities. Let's get those going next!
