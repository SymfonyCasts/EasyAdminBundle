# Crud Controller

Coming soon...

The true reason you use easy admin is for its crud controllers. Each crud controller
will give us a rich set of pages to create, read, update, and delete a single entity.
This is where easy admin shines and the next few minutes are gonna be critically
important to understanding how easy admin works. So we have four entities let's
generate a crud controller. First for question to do that. Find a terminal on Symfony
console. So bin console make admin crud. As you can see, it recognizes our four
entities. I'll hit one for question. Let this generate into the default directory and
with default namespace. Sweet. This did exactly one thing. It graded a new question.
Crud controller file.

So let's go open that up now, before we look too deeply into this head over to the
admin page and refresh two C absolutely no difference. We do have a new question
credit controller, but these credit controllers are kind of useless until you link to
them from a dashboard. So back over on a dashboard and troller down at the bottom
yield menu item, but instead of link to dashboard, there's a number of other things
that you can link to. What we wanna do is link to crud. We're gonna pass this a
couple things, the label. So questions in icon. So Fafa dash question dash circle.
That's a fun, awesome icon. And then most importantly, the entity's, uh, class name.
So question Colin class behind the scenes, easy. Admin's gonna recognize that there's
only one crud dashboard for this entity, which is question crud controller, and it
will know to use this. And yes, in theory, you can have multiple crud control,
thrillers per entity, and that's something we'll talk about later. All right, so now
refresh new link, click and whoa. This is super cool. We have an is approved slider,
which saves automatically love that we have a search on, on top love. That also will
also sortable columns.

We can edit. We can delete the edit even has a cool calendar. I mean, already we are
absolutely loaded with cool stuff. It's pretty freaking cool. So let's repeat this
for our other three controllers. So I'll head back to my terminal run Symfony
console, make admin credit again, let's get one for answer with the defaults stuff.
One for topic with the default stuff. I'm gonna clear my screen again. And the last
one we need is for user beautiful. So no surprise. The only thing I did is it created
three more of these crud controller classes, but to make those useful, we need to
link to them. So I'm going to paste three more links here. Let's say answers And I'll
fill in some And I'll customize each of these links. Beautiful. And I wanna refresh,
yes, we have instantly four different admin sections. All right. So I wanna just look
a little deeper into how this is working behind the scenes. So go to question crud
controller and look at its base class. So I'll hold commander control and jump into
abstract crud controller. So we saw earlier at our dashboard extends in abstract
dashboard controller. Our crud controllers extend as abstract crud controller.

So pretty much everything about how our crud controller works is going to be
controlled by overriding, uh, these configure methods that you see inside of here.
We're gonna talk about, we're gonna learn about all these as we go along, but on a
high level, configure crud is to help you configure the entire crud section.
Configure assets allows you to control custom CSS and JavaScript in this section,
configure actions allows you to control. So what actions you want, which are things
like having an index page, having an edit action or having a delete action more on
that later.

And then really the last important one is configure fields, which is gonna configure
which fields we see on the index page and also which fields we see on the form. But
don't worry about those specifically yet, because we're gonna see all those later
then below this, you can actually see all of the code that executes the pages. So
index is our index page. Uh, detail is a detail show page, uh, edit is the edit form.
So you can see the code that actually runs behind all of this, which is super useful
way to figure out how to extend things. Now, wait a second, though, because if you
scroll back up here to the configure methods, some of these configure methods look
familiar. Some of these also exist in the dashboard based controller class,
specifically configure assets, configure crud, configure actions, and configure
filters. These all live side of dashboard inside of abstract dashboard controller,
there's configure assets, and then further down here, crud actions and filters. So
what's going on.

All right. Here's what's going on. And it is, are important. Look at the URL that
we're on. Quick questions. Look at the URL up here. It starts with /admin and then a
bunch of query parameters turns out that everything in easy admin is handled via a
single giant route. Everything runs through the dashboard controller, route the
/route, the route that's above the index. So when we go to the question credit
controller, it's actually matching this route right here with extra query parameters
to say which crud controller and which action runs. You can see crud controller, peer
and crud action right there.

So we're rendering. When we go here, we're rendering question, crud controller, but
in the context of our dashboard controller, anyways, when we go to this page in order
to get the crud config, easy admin first calls, configure crud on our dashboard, and
then it calls configure crud on our specific ride controllers. In this case question,
crud controller, this is super powerful. It means that we can override things on a
dashboard level, like set some crud settings that apply to all sections or for one
specific, uh, crud controller. Watch I'll prove it. So back in abstract, uh,
dashboard controller, configure crud, uh, everyd area has four pages. I'm actually
gonna hold command and open this crud thing. There's some constants on the top of
here. So every crud section has an index page. That's this in edit page, a new page,
and also a detail page. Each page can then refer to a set of actions. These are links
or buttons. So for example, on the index page, right now, we have an action for a new
action. We have an action for editing and we have an action for deleting and we could
configure, we could add more actions or remove those actions.

All, you can see how this is configured down and configure actions. So this is for
the dashboard. So this is the conf action configuration that applies to all of our
sections. You can see that for the index page. It acts adds new edit and delete
actions for the detail page there's there's edit index and delete. And if you're on
the edit page, you of the actions save and return and save and continue. So one thing
you notice if you look closely here is that we do have a detail page, but nobody
actually links to it. You don't see page detail. Um, you don't see action calling,
calling detail, uh, on any of these pages. So the page exists, but it's not really
used out of the box. So let's add that. What we're gonna do is we're gonna go to
dashboard controller and it doesn't matter where, but I'll go down to the bottom. I'm
going to go to co generate or command and on the Mac go to override method and
override configure CRUT. So first thing we do wanna call parent configure CRUT. So it
can instant that object. And then we're gonna say, add, oh my bad.

So actually we wanna go to override methods and we wanna do configure actions. So we
do wanna call the parent methods that I can create that the actions object, and, um,
set up all those actions, default actions for us. Now we're gonna say add, and what
we're gonna do is we're gonna add an action to our index page. So we can use that Cru
class call on page index. So we're in, and then the action we're gonna add. We can
actually use a constant for this as well. Action, colon, colon detail. So on the
index page, I want to add the action, the detail action. The result of this, when we
refresh the question credit controller is that we have this show link that goes to
the detail action, and we can go to any section. And it now as that, we just modified
every single credit controller in the system. But now since this topic, uh, entity is
so simple, let's disable the Cru the detail action for just this topic section. So to
do that, open up topic, crowd controller, and just like before, we're gonna go to co
generate or command M the Mac go to override methods and override that same configure
actions.

So by the time this method is called, it's gonna pass us. The actions of that was
already set up by our dashboard. So it's already, already going to have the detail
action enabled for the index page, but now we can control that by saying disable
action co detail. We're gonna talk more about the actions configuration, but these
are the main things that you can do inside of them. You can add a new action to a
page, or you can just completely disable an action globally for all of your, uh,
pages. So as soon as we refresh here, as our detail action is gone, but if, if we go
to our other sections, it's still there.

So the big takeaway here is that everything is going through our dashboard
controller, which means that we can configure things on a dashboard level, which will
apply to all of our crus, or we can configure things for one specific crud. Now, the
fact that all of the crud controllers go through this /avenue, where all has one
other effect related to security, it means that all of our controllers are already
secure that's thanks to our access control. So remember back in config packages
secure to that YAML, we had a little access control here that said if the URL starts
to /admin require roll_admin. So all of these are automatically now without doing
anything else, requir role admin, we'll talk more later about how to secure different
admin controllers with different roles, but at the very least, you have to have role
admin to get anywhere, which is awesome. But one important point, adding this access
control was necessary. Here's a, even though the URL is /admin, when you go to a crud
control like this, it does not exit cute our dashboard controller index action. So
this is a little bit confusing.

This action here is what has our route.

When we go to a crowd controller like this, it does match this route, but easy admin
does something crazy. Instead of allowing Symfony to call this controller. It sees
these crud, this crud controller query parameter here and magically switch is the
controller to be the real controller. In this case, to be question crowd controller
index, you can actually see this down here on the web Depot toolbar. If you hover
over these a admin at admin, tell this tells you that the route name is admin. So the
route name is actually this route right here, but the controller is question crud
controller, call on call an index. So there's some route magic happening, but
ultimately the methods in your crud controller are the ones that are called. So it's
the index method in this abstract crud controller down here. This is actually the
real roller for the page. This is important for security, just because if we had only
put the route and the route attribute above index and not added and not added the
access control, that wouldn't have been enough

<affirmative>

Because This controller and therefore this, sorry, this is granted attribute is only
enforced when you actually go to your dashboard page. Anyways, some of that is still
a little fuzzy. Don't worry. Stick with me. We're gonna keep going through self. That
was a blast of sort of easy admin theory to help us understand things a little bit
better as we dig further and next, before we go deeper into our credit controllers,
let's, let's, let's mess around a bit more with our dashboard by adding some custom
links to our menu and also our user menu.

