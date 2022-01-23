# Assets

The EasyAdmin interface looks pretty great out of the box, but what if we want to customize the way something looks? For example, I want to change the background on this sidebar. How can we do that? This type of stuff can be controlled via the `configureAssets()` method. As a reminder, this is a method that exists on both our base controller *and* inside of our individual CRUD controller. So we can control assets on a global level, *or* we can control assets by overriding the same method inside one of our credit controllers. Let's do ours globally so we can change the color of the sidebar for every single page.

Anywhere inside of my `DashboardController`, I'll go back to the "Code Generate" menu, select "OverrideMethods" and override `configureAssets`. This has a lot of cool methods on it. There's some simple ones like `->addCssFile()`. So if you said `->add.CssFile('foo.css')`, that's going to include a link tag to `/foo.css`. As long as I have `/foo.css` inside of my public directory, that would work. The same thing goes for `->addJsFile()`. You can also `->addHtmlContentToBody()` or `->addHtmlContentToHead()`. There's a ton of interesting methods!

In our case, my application uses Webpack Encore. In my project, I have `webpack.config.js` and it's very simple. I only have one entry called `app`. This `app` entry is what I have on my front end, and it's responsible for loading all of the JavaScript in the application as well as this CSS file, so that's what gives us all of our front end styling.

You probably noticed that there is an `addWebpackEncoreEntry` that we can do here. So if I said `'app'` here, that would pull in the CSS and JavaScript from my app entry. *But* that makes things look a little crazy because we don't want all of our front end styles and JavaScript to show up the admin section. We just want to be able to add a *little bit* of new stuff.

Here's what we'll do instead. Inside the `assets/styles/` directory, let's create an entirely new file here called `admin.css`. This will be our CSS solely for configuring the admin section. And just to see if things are working, I'll add a very lovely body background of "lightcyan". Ooh... fancy.

Over in `webpack.config.js`, I'm going to add a second entry for *just* the, admin. But, right now, since I only have a CSS file (I don't need JavaScript), I'll say `.addStyleEntry`... call it `'app'`... and point it to `.assets/styles/admin.css`.

Perfect! Because I just modified my Webpack file, I need to go over to my terminal, find where I'm running Webpack, hit "ctrl + C", and then rerun it.

And... it exploded. You probably saw me make a mistake there. I need to give my entry a unique name, so I'll change `'app'` to `'admin'`, run it again, and... beautiful! And now, in addition to the original stuff, you can see it also dumped an `admin.css` file. So, thanks to this, over in our `DashboardController`, we'll do `->addWebpackEncoreEntry('admin');`. 

Refresh and... it works! Lovely.

If you go to "View Page Source", you can see how this works. There's really nothing special here. The `app.css` file here gives us all of the EasyAdmin styling that we've been enjoying, and *here* is our new `admin.css` file.

At this point, we're dangerous. We can add whatever CSS we want to this new `admin.css` file, and it will override *any* of the EasyAdmin styles. Cool! But EasyAdmin makes it even easier than that!

Inspect the element on the sidebar here. I just want to change this background a little bit. Find the actual element with the `"sidebar"` class. If you look over here...I'll make this a little bit bigger... you can see that we have `.sidebar` `background`, but instead of being set to a color, it's set to this `var(--sidebar-bg)` thing. If you hover over it, you can see that, apparently, it's equal to `#f8fafc`.

If you haven't seen it before, this is a CSS property. It has nothing to do with EasyAdmin or Symfony. In CSS, you can create variables (called "CSS properties") and reference them somewhere else. EasyAdmin has created a `--sidebar-bg` variable, and it's referencing it here. So, instead of trying to override the background of `.sidebar`, we can just override this CSS property and it will have the same effect. How do we do that? I'm gonna have you cheat a little bit. We're going to dig down deep into EasyAdmin itself.

I'll open `vendor/easycorp/easyadmin-bundle/assets/css/easyadmin-theme/`, and inside of here, there's a file called `variables-theme.scss`. This is where all of these CSS properties are defined. There's tons of stuff here: all of these font sizes, different widths, and then, right here, `--sidebar-bg`. This `--sidebar-bg` variable is actually set to another variable - this `var` syntax. You'll find *that* variable in one other file called `./color-palette.scss`, which is right here. These are SCSS files, but this CSS variable system has nothing to do with SCSS. It's a pure CSS feature.

You can see a lot of other variables inside of here as well. If you follow the logic here, `--sidebar-bg` is set to `--gray-50`. Scroll all the way to the bottom here, and you'll see `--gray-50`, and *it* is set to `--blue-gray-50`... and you can see the value of *that* up here.

Anyways, this is a way of learning how these values relate to one another and how to override them. I'll copy this syntax here.

The way you define CSS variables is typically under `:root`, so we're going to do the same thing here. I'll get rid of my body, say `:root`, and then say `--sidebar-bg`. While it's totally allowed to reference variables here, I'll just replace this with a different hex color.

All right, let's go try it. Refresh!

Hmm... That did *not* look like it worked. Over here, you can see it still says `--sidebar-bg`. Let me do a "cmd + shift + R" to force refresh and... got it! You can see my new `#deebff`. It's subtle, but it *is* loading the correct color now.

So we just customized the assets globally for our entire admin section. But we *could* override `configureAssets()` in any of our CRUD controllers if we needed to do something only for a specific controller.

Next, let's start digging into what is quite possibly the most important part of configuring EasyAdmin: Fields. These control which fields show up on the index page, as well as the form pages.
