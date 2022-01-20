# Override Template

Coming soon...

Everything you see in easy admin from the layout to this table, to even how the
individual fields are rendered is controlled from a template. Easy admin bundle has a
bunch of templates and we can override all of them. We looked at these a bit earlier.
Let's dive in back into easy admin vendor, easy Corp, easy admin bundle, source
resources and views. So there's a lot of good stuff here. Layout that HT twig. This
is the layout that's used by every single page. We also saw a few minutes ago,
content HTML, twig. This is a really nice, uh, layout template that you can extend if
you're creating a page inside of, um, easy admin. And then of course, maybe most
importantly is in inside of crus.

Instead of crud, you can see the templates for the individual pages and inside of
field. We have a template that controls how every single field type is rendered. Now
I notice one thing, you'll see things in these templates like EA dot template path
layout, to understand that I'm gonna hit shift shift and open up a class called
template registry. This is actually a file. This is a file inside of easy admin. And
we actually opened this up earlier in internally, easy admin maintains a map from
sort of a template name to an actual template path. So when you see something like EA
dot template path layout, that's gonna use a template registry to figure out that
it's gonna load at easy admin /layout, where at easy admin is, is an a twig alias
that points to this view is directory. So just kind of keep this map in mind. All
right. So here's my goal. I want to override the layout and add a level footer that
appears on the bottom of every single page. So if I look over at layout dot H to my
twig near the bottom, let's see, I'm actually gonna search. Lemme just search for
footer. Cause I know it's there. There we go. We have a block inside of here called
content footer. So if you define a content footer, then it will get dumped out right
here. So let's override layout that HTML twig and add a content footer.

There are two ways to do this. The first way is just to use Symfony's normal system
for overriding templates that live inside of a bundle. The way you do that is by
creating a very specific path. So inside of templates, you create a directory called
bundles inside of that, another directory, that's the name of the bundle. So easy
admin bundle. And then the, instead of here you match whatever path you wanna
override down here. So we wanna override layout dot HTL twig. So here I'll create a
new file called layout dot HTL twig. Now I don't really want to override this
entirely. I want to extend it so we can say curly brace extends. We can say at easy
admin <affirmative> /layout dot HTL dot twig. The only problem with that is that now
that we've overridden this using this, this, uh, syntax, anytime this template name
is reference, it's going to think it's this template we're kind of effectively
extending ourselves. Symfony's gonna look at this and open this template, which is
gonna load this, which is gonna load this template. So to tell Sy that we wanna sort
of use the original one. We don't want to take our override into account is we can
put an exclamation point on front. We can put an exclamation point in front.

Perfect. Now down here, let's over at our block block content, footer and block

<affirmative>

And I'll put our nice little footer there. All right, let's try it. Refresh any page
and hello, footer. Sweet. Now I mentioned that there's two ways to override
templates, an easy ad one. And the second one is even more powerful

<affirmative>

Because we can control exactly when we want our override templates to be used. Let me
close a couple of our files here. So here's my goal globally. I want to override the
template. That's used to render ID fields. I wanna add like a little key icon now to
the ID. Now, if you open the ID field, I'll click to open that class. You can see
that it sets its template name to crud field ID, and that corresponds to the template
registry. So crud field ID. Here we go. Crud field ID corresponds to this template
right here, which we can find down in here in our resources, views, crud, field ID
dot H by the way, pretty much every, no, I'm not gonna say that. So what I wanna do
is override this template, but for the whole system, so that all ID fields
automatically use it. So first let me copy this template and let's create our
override. So templates admin field, I'll create ID with icon dot H channel do twig.
And I'm gonna paste that in there. I'll put a little icon right before this. Now just
by grading. This file right here does absolutely nothing to activate this globally.
We'll go into our dashboard controller cuz that we do global things and you can
actually configure template, override paths down and configure crud. So check this
out. We can say->override template, and here we're gonna use the name of our
template. That's the thing that you see inside template registry or the thing that
you see inside of I ID field. So globally, whenever we are under this crud field ID,

Let's now have that point to admin /crud /field /ID with Icon HTML, that twig, how
cool is that? Let's try it refresh and oh, And let me rid of my extra crud there. Try
that. There we go. And awesome. We get our key icon across the entire system. How
powerful is that? Oops, lemme stay in my admin area And we can even improve this a
little bit. The ID template is super simple, cause it just prints out the format of
value, but sometimes a template might do a little bit more complex stuff. And instead
of repeating it down here, you can just include the original template. So quite
literally include, And I'm just gonna start typing ID dot H twig and let that auto
complete that for me over here, we'll get the same result. Super nice Or let's do, do
one more kind of playing thing with the templates over on the user's index page. You
see this rolls list, it's got these cool badges, but this rolls list is a little bit
long and it uses kind of these very internal roll names. I wanna override this
template and make it look a little bit different.

No, I'm not gonna do this Next. Let's start talking about permissions, how we can
deny access to entire crowd controllers or even specific actions based on the user's
role.

