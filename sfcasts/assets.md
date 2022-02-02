# Assets: Custom CSS and JS

The EasyAdmin interface looks pretty great out of the box. But what if we want to
customize the way something looks? For example, if I want to change the background
on the sidebar. How can we do that?

This type of stuff can be controlled via the `configureAssets()` method. As a
reminder, this is one of those methods that exists inside both our dashboard
controller *and* each individual CRUD controller. So we can control assets on a
global level *or* for just one section.

Let's make our change globally so that we can change the color of the sidebar on
every page.

## Hello configureAssets()

Anywhere inside of `DashboardController`, go back to the "Code"->"Generate..."
menu, select "Override Methods" and override `configureAssets()`:

[[[ code('e1bb289ec7') ]]]

This has a lot of cool methods. There are some simple ones like `->addCssFile()`.
If you said `->addCssFile('foo.css')`, that will include a `link` tag to `/foo.css`.
As long as we have `foo.css` inside of our `public/` directory, that would work.

The same thing goes for `->addJsFile()`. And you can also `->addHtmlContentToBody()`
or `->addHtmlContentToHead()`. There are *tons* of interesting methods!

## Creating a Custom Admin Encore Entry

*Our* application uses Webpack Encore. Go check out the `webpack.config.js` file:
it's pretty standard. We have just one entry called `app`:

[[[ code('f0d21ea764') ]]]

It's responsible for loading all of the JavaScript *and* CSS:

[[[ code('ffebc503b7') ]]]

and we include this entry on our *frontend* to get everything looking and working well.

You probably noticed that, in `configureAssets()`, there's an
`addWebpackEncoreEntry()` method. If we said `app` here, that would pull in the
CSS and JavaScript from our `app` entry. *But*.... that makes things look a little
crazy... because we do *not* want *all* of our frontend styles and JavaScript to
show up in the admin section. Nope, we just want to be able to add *a little bit*
of new stuff.

So here's what we'll do instead. Inside the `assets/styles/` directory, create an
entirely new file called `admin.css`. This will be our CSS solely for styling
the admin section. And just to see if things are working, I'll add a very lovely
body background of "lightcyan":

[[[ code('ae11b3dd02') ]]]

Fancy!

Over in `webpack.config.js`, add a second entry for *just* the admin. But, right
now, since we only have a CSS file (we don't need JavaScript), I'll say
`.addStyleEntry()`... and point it to `./assets/styles/admin.css`. I should also
change `app` to `admin`... but I'll catch that in a minute:

[[[ code('68fbb7de7c') ]]]

Because we just modified our webpack file, we need to go over to our terminal,
find where we're running encore, hit `Ctrl`+`C`, and then rerun it:

```terminal-silent
yarn watch
```

And... it exploded! That's from my mistake! I need to give my entry a unique name.
Change `app` to `admin`:

[[[ code('811663d0f2') ]]]

Run it again, and... beautiful!

In addition to the original stuff, you can see that it also dumped an
`admin.css` file. Thanks to this, over in our `DashboardController`, say
`->addWebpackEncoreEntry('admin')`:

[[[ code('9cb529831f') ]]]

Refresh and... it works! That's a... well... interesting-looking page.

If you View the page source, you can see how this works. There's really nothing
special. The `app.css` file gives us all of the EasyAdmin styling that we've been
enjoying... and then *here* is our new `admin.css` file.

## CSS Properties

At this point, we're dangerous! We can add whatever CSS we want to the new
`admin.css` file and it will override *any* of the EasyAdmin styles. Cool! But
EasyAdmin makes it even easier than that!

Inspect the element on the sidebar. The goal is to change the sidebar background.
Find the actual element with the `sidebar` class. If you look over at the styles
on the right... I'll make this a little bit bigger... you can see that the
`.sidebar` class has a `background` style. But instead of it being set to a color,
it's set to this `var(--sidebar-bg)` thing. If you hover over it, apparently, this
is equal to `#f8fafc`.

If you haven't seen this before, this is a CSS property. It has nothing to do with
EasyAdmin or Symfony. In CSS, you can create variables (called "CSS properties") and
reference them somewhere else. EasyAdmin, apparently, created a `--sidebar-bg`
variable and is referencing it here. So, instead of trying to override the
background of `.sidebar` - which we *could* do - we can override this CSS property
and it will have the same effect.

How? Let's cheat a little bit by digging deep into EasyAdmin itself.

Open `vendor/easycorp/easyadmin-bundle/assets/css/easyadmin-theme/`. Inside,
there's a file called `variables-theme.scss`. *This* is where all of these CSS
properties are defined. And there's *tons* of stuff here, for font sizes,
different widths, and... `--sidebar-bg`! This `--sidebar-bg` variable,
or property, is apparently set to *another* variable via the `var` syntax. You'll
find *that* variable in another file called `./color-palette.scss`... which is
right here. These are SCSS files, but this CSS property system has *nothing* to do
with Sass. This is a *pure* CSS feature.

There's a lot here, but if you follow the logic, `--sidebar-bg` is set to
`--gray-50`... then *all* the way at the bottom, `--gray-50` is set to
`--blue-gray-50`... then *that*... if we keep looking... yes! It's set to the
color we expected!

This is a great way to learn what these values are, how they relate to
one another and how to override them. Copy the `--sidebar-bg` syntax.

The way you define CSS variables is typically under this `:root` pseudo-selector.
We're going to do the same thing.

In our CSS file, remove the `body`, add `:root` and then paste. And while it's
*totally* legal to reference CSS properties from here, let's replace that with
a normal hex color:

[[[ code('8976440c6a') ]]]

Let's try it! Watch the sidebar closely... the change is subtle. Refresh and...
it changed! To prove it, if you find the `--sidebar-bg` on the styles and hover...
that property *is* now set to `#deebff`. It's subtle, but it *is* loading the correct
color!

So we just customized the assets globally for our entire admin section. But we
*could* override `configureAssets()` in a specific CRUD controller to make changes
that *only* apply to that section.

Next, let's start digging into what is quite possibly the *most* important part of
configuring EasyAdmin: Fields. These control which fields show up on the index page,
as well as the form pages.
