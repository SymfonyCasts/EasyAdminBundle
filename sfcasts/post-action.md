# Post Action

Coming soon...

The whole point of this pending approval section is to allow moderators to approve or
delete questions. We can delete questions, but there's no way to approve them. Sure.
We could add a little is a approved checkbox to the form, but a true approved action
that we can show on the details page or the index page would be a lot nicer. And it
would also allow us to run some custom code on approval if we need to. So let's
create another custom action over in question crowd controller. We already know how
to do this. I'll create a new approved action variable = action, a new, and I'll make
up the word approve Then down here at the bottom. Let's add that to the detail page.
So add crud page detail, approve action. And before I try that, we know we're gonna
wanna do some customizations. I'm gonna give this some CSS classes, An icon,

You know, also gonna say display as button by default in action displays as a link
where the URL is wherever you want it to go to. But in this case, we don't want
approval to be done with a simple link that makes a get request, approving something
is gonna modify data on the server. So we want this to be a post request. So this is
going to render it as a button. Instead, you see how that works in a minute. Now, as
we remember, you can't create an action. You need to actually link it to a URL, a
route, or a crud action. In this case, we need a crud action. That's gonna allow us
on click on click. It's gonna take the user to new action inside of this controller
that we are going to write in a few minutes. So say link to correct action. And the
name of the method that we're going to create in a few minutes will be how about
approve? All right, refresh

<affirmative>

And the button isn't there. The button won't be there on the edit page, but if we go
back to the show details, beautiful, there it is up approve, but inspect this element
and check out the source code. So we can see there's literally a button here and
that's it. There's no form around this and there's no JavaScript magic to make this
to. In other words, I can click this all day long and absolutely nothing is going to
happen.

We need to wrap this in a form so that we can click this and submit that form to our
new URL, how we by leveraging a custom template. So deep inside of easy admin, we
know that every easy admin has lots of templates. So in easy admin in the resources,
views directory and inside crud, there's an action that HT that twig, this is the
template that's responsible for or rendering every action you can see either, either
it's either an, a anchor tag or it's a button based on our configuration. So I'm
gonna copy these three lines up here that help hint what variables we have and let's
go create our own custom template. So I'll do it in Templates, admin, and here, I'm
going to say approve_action.ht, all that twig, pace that in. And then just to other
help us know what's going on. Let's dump that action variable. Then to use this over
in question credit controller, actually right here,

I'll say set template path, and it's going to be admin, okay. /approve action. That
HTL that twig. All right, let's try it. Refresh and cool. We see the dump and we can
see all the data on that action. DT E object. The most important thing for us is link
URL. This points to the URL we need to go to, to execute our new approved action. And
because our new template is only being used by this action, and we're free to do
whatever we want in here. All the other actions are still using the core action that
HTM of that to template. So what we wanna do here is actually make a form form action
equals, and then we can use curly curly action, link URL, and then method = post.
Instead of here, we want the button, we could create it ourselves, but an easier way
to do it is just to include The original, easy admin crud action. Do H on that twig.
Perfect. Reload the page, and now inspect that element to see exactly what we want
the form with the correct action. Our button inside though, you kind of wanna fix the
styling there a little bit. I'm just gonna do that

Classical Mr. Dash one. No, that's not the right thing. What is it? M E dash one.
That's what it is. Classic = M E dash one M E two. Wanna refresh? Cool. Looks better.
All right, so let's try clicking this. We click it and a giant error. The controller
for /admins dot callable expected method approve on our class and it's not there. So
final step. We need to write proper action. So in question, crowd controller, All the
way down on the bottom, this is just a normal action. You can almost pretend like
you're just writing this a normal Symfony with a route above this, and it's just
public function of proof.

And now we're just going to get to work. Now, when we get to this URL inside of here,
it's going to contain the ID of whatever the entity is that we click that button for
to get that we can get that from, from E Z admin to do that. We need to auto wire, an
admin context service. So two things about this admin context holds everything about
easy admin. An admin context is a service just like the entity manager or the router
and our approved method is a completely normal Symfony controller. So what we're
doing here is the normal auto wiring of a service that you're used to seeing in all
of your normal controllers. So now we can say question = admin context, arrow, get
entity arrow, get instance, sometimes finding the data you need in admin context
requires a little digging, and then I'm doing a little sanity check here, mostly for
my editor. If not questions and instance of question, we'll throw a new logic.
Exception.

Entity is missing or not a question. Then down here, we can very easily say
questionnaire set is approved true. Then to save this, we know how to do that. We
need the entity manager. And since this is a normal controller method, we can auto
wire entity manager, interface, entity manager down here say entity manager,->flush.
Finally, we need to do something here. Now, if you want to, you could render a
template and sometimes you will create actions that are not meant to, that are
literally a new page in your admin section and rendering a template here is nothing
special. We already did that inside of our dashboard controller. I, our index method
is a regular action where we rendered a template. So if you wanted to render a
template, it would look pretty much exactly like this, but in this case, we want to
redirect.

Now you probably know how to redirect from inside of a controller, but in this case,
I wanna redirect <affirmative> back to the detail page in our admin. And you might
remember from a little earlier, no generating in order to generate a URL to somewhere
in the admin section, and you actually need a special admin URL generator service
that can help you get the exact right query parameters. Unfortunately, it's also
available as a service. So I'll say admin URL, generator, admin URL generator, and
then we can say target URL = and basically to start building the URL we want. So
admin URL, generator->set controller, I'll say self class. We're gonna link back to
ourself set action crud on page detail, set entity ID, question, arrow, good ID, and
then finally generate URL at the bottom. And there are a number of other methods you
can call on this builder to kind of set a couple of different things here, but those
are the most important ones. Then at the bottom return, this->redirect Target URL.
All right, let's give that a try, refresh and got it. We are back on the details
page.

And if we look for Alice thought she over here, it's not on our question, improving
pending approval page anymore. So that kind of proves that it was approved here.
Let's look at an easier one here. I'll approve ID 23. So I got a show approve and
it's gone. It's working. The only problem, the only small problem now, which you
probably saw is that when you do go to the show page detail page on an already
approved question, you see the approved button click on that won't hurt anything, but
that doesn't really make any sense to have up there. Fortunately, we know how to fix
this, find our custom action. And we can say, add the display if to that, pass that a
function I'll put static on there.

A

This will receive the question argument. We'll return a boo. I've been a little lazy
on my return types, but you can put that if you want. And I'm gonna return not
question arrow. GI is approved. I move over.

Okay, cool.

Refresh and beautiful that button's gone. But if you to go back to one that does need
to be approved still there. If we wanted, we could go further and write some
JavaScript for this action. For example, in our custom template, we could use

The stimulus controller function to reference some stimulus controller that we write.
Then on click of this button, we could open a mode that says, are you sure you want
to approve this question? The point is we control what this action looks, link or
button or whatever you want looks like. So if you wanna attach some custom
JavaScript, you can totally do that. Next let's talk. Let's add a new glow global
action. And global action is something that applies to all of the items inside of a
section. We are going to create a global export action that will export questions to
CSV.

