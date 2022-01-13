# Assets

Coming soon...

The easy admin interface looks pretty great out of the box, but what if we wanna
customize the way something looks? For example, I wanna change the background on this
sidebar. How can we do that? This type of stuff can be control via the configure
assets method. As a reminder, this is a method that exists both on our base
controller and it also exists inside of our individual crud controller. So we can
control assets on a global level, or we can control assets by overriding the same
method inside. One of our credit controllers let's do ours globally, so we can change
the color of the sidebar for every single page.

So anywhere inside of my dashboard controller, I'll go back to the command generate
menu. I'm go, I'll go back to override methods and override configure assets. Now
this has a lot of cool methods on it. There's some simple ones like add CSS file. So
if you said, add CSS file food CSS, that's gonna include a link tag to /food CSS. So
as long as I have a food at CSS inside of my public directory, that would work
there's same thing for ad JS file. And there's other things like you can add HTML
content to the body, HTML content to the head. There's lots of interesting methods
now in our case, my application uses Webpac ENCO.

So in my project, I have a Webpac that config that JS, and it's very simple. I just
have one entry called app. That app entry is responsible for loading a, all of the
JavaScript and application and also loading this CSS file. So this app entry is what
I have on my front end. And that's what gives us all of our frontend CSS and frontend
JavaScript. Now you probably noticed that there is a ad Webpack Encore entry that we
can do here. So if I said app here that would actually bring in the CSS and
JavaScript from my app entry, which will completely make things go crazy because we
don't want all of our front end styles and JavaScript in the admin section. We just
wanna be able to add a little bit of new stuff.

So here's what we're gonna do. Instead inside the asset styles directory, let's
create an entirely new file here called admin CSS. So this will be our CSS just for
configuring the admin section. And just to see if things are working, I'll add a very
lovely body background of light cyan. Ooh. Now, over in Webpac and fig digests, I'm
gonna add a second entry for just the, um, admin, but right now, since I only have a
CSS file, I don't need JavaScript. I'm gonna say add style entry, call it app and
point it at /assets /styles /admin dot CSS.

Perfect. Now, because I just modified my Webpack file. I do need to go over to my
terminal. Find where I'm running Webpack, hit control C and then rerun it. Ooh. And
it exploded. Oh, you probably saw a mistake there. Little bit of copy, pasta need to
give my entry a unique name and beautiful. Got it. And now you can see it dumped in
addition to the original stuff. It dumped in admin dot CSS file. So thanks to this
over in a dashboard controller, we'll do ad Webpac on entry admin. So refresh and it
works what a beautiful background that is really lovely.

And if you go to view page source, you can see how this works, nothing special here,
this first button, this first app, that CSS that's what, what gives us all the easy
admin styling that we've been enjoying. And now here's our new admin CSS file. So at
this point, we're dangerous. We can add whatever CSS we want to this new admin CSS
file, and it will override any of the easy admin styles. Cool. But the admin makes it
even a little bit easier than that. So inspect the element on the sidebar here. Cause
ultimately I just wanna change this background a little bit and find the actual class
with the sidebar element with the sidebar class. If you look over here, you'll see,
I'll make this a little bit bigger that we have sidebar background, but instead of
being set to a color, it's set to this like VAR dash sidebar dash BG thing, however,
over it, you can see that that apparently is equal to F H F a F C.

So if you haven't seen it before, this is a CS says property. It has nothing to do
with easy admin or Symfony. It's just in CSS. You can basically create variables,
they're called CSS properties and then reference them somewhere else. So easy admin
has created a sidebar, BG variable, and they're referencing it here. So instead of
trying to override the background of dot sidebar, we can just override this CSS pro
pro and it will have the same effect. How do we do that? Well, let's, I'm gonna have
you cheat a little bit. We're gonna dig down deep into easy admin itself. So I'm
gonna open vendor, easy Corp, easy admin, go to assets, CSS, easy admin theme. And
then inside of here, there is a file called variables theme SCSS. And this is where
all of these CSS properties are defined. There's tons of stuff like all these
different font sizes, different widths that are, uh, defined. And then right here,
sidebar BG. So this sidebar BG variable is actually set to another variable, this bar
syntax. And you'll find that variable in one other file called color palette. That
SCSS, which is right next to this notice, these are SAS files, but this CSS variable
system is nothing to do with SAS. It's a pure CSS feature. So you can see lots of
other variables inside of here. So if you kind of follow the logic here, sidebar BG
set to gray, 50 and gray 50, let's see, where is the gray inside of here?

You scroll all the way to the bottom. You'll see gray 50, it's the two blue, gray 50.
And you can kind of go from there where we eventually find where that is. Anyways,
this is a right way of knowing how to E easy, getting an idea of how to override
things. What we'll do is I'm actually going to copy this syntax here and the way you
define CSS variables typically is under this little weird colon root thing. So we're
gonna do the same thing here. I'm gonna get rid of my body, say colon root, and then
say cyber BG. And I can, uh, I can reference variables here. That's totally allowed,
but I'm just gonna replace this with A different hex color. All right. So we try it
now. Hmm. Did not look like it worked. You can see over here. It's so sad. Let me do
a command shift. R do force refresh and Got it. So you can see my new D E BFF it's
subtle, but it is loading the correct color now. So we just customize the assets
globally for our entire admin section, But we could override configure assets in any
of our crud controllers. If we needed to do something only for a specific controller.

All right, next, let's start digging into what is quite possibly the most important
part of configuring easy admin fields. These control, which fields show up on the
index page, as well as the form pages.

