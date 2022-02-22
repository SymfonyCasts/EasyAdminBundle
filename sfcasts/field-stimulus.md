# Field Stimulus

Coming soon...

We're going to apply this controller to
the
row that's around our field. So let me make things a little bit smaller here. So
we're going to apply the controller to this row. That's around here. We're going to
add
a target to the actual text area itself and an action. So the end result of this is
that when this controller is initialized, it's going to create a little markdown
preview
dev. And then whenever we type into the field, it's going to call render, which is
going
to re render the, uh, preview into the box. If you have any, we're not going to go
too
much into this. You have any questions about this control it's you can ask in the
comments,

But thanks to the fact that admin JS is importing bootstrap. That's initializing our
controllers. This snark down controller is already available in our admin section to
connect it to our field.

We're going to say set form type options past this NRA. And we're going to add a
couple of
attributes. The first thing I wanna add is row_ATTR. This is attributes you wanna add
to the form row. This is not an easy admin thing. This is just a, uh, an option
inside Symfony's form system. Here. We're going to say data-controller. This is how
you initialize a stimulus controller, and we're going to say snark down next, pass an
ATTR option. This is going to be attributes to it or applied to the text area itself.
We
need to here the first one's data-snark down-target set to input that adds
a target to our stimulus controller called input, that points to our text area. And
the next one is data action set to snark down name of our controller pound sign
render. What that's going to do is it's going to say whenever the text area changes,
call
the render method on our snark down controller, which is what's going to update the
markdown preview. All right. So let's try it. I'm going to go over and refresh and
actually let's do a, let's do a force refresh and Hmm. I don't see anything that's
like in our console here. That looks fine.

Let me make sure that my actually let's inspect element on this debugging time. Oh,
there it is right there. You guys probably saw data. <inaudible> go SAR down. So if
you don't type of that, my controller is never actually initialized snark down.
<affirmative>. Now, when we refresh, there we go. A little more luck. You can see our
preview down there and as I type it instantly updates to make something bold. Awesome
though, we could probably fix the styling here a little bit. So fortunately, we also
know how to add styles to our admin area. So I'll go to our admin.CSS file and
I'm AOL markdown preview selector here. This is a class that our controller adds to
the preview area. So we're going to take advantage of that to add a little bit of
Styling to it. Now, if we try this much better, I love that Actually, to improve this
even a little bit more, check this out in question credit control arm, I call one
more method on here called set help, and just say preview, cuz the help message
renders below the field. There he goes. It gives a nice little preview thing right
there. Now one warning is that if you use stimulus every controller in your
controller's directory,

Including our snark down controller is going to be, I include it in the build app dot
JS, which is the file that's on the front end. In other words, our frontend users
right now that go on the main part of our site. They are now downloading our snark
down controller and snark down itself. That's not really a problem. It's not a
security problem. It's just wasteful because we are not using these on our front end.
So the easiest way to fix this

Is to go into our controller and add a little superpower. That's special to stimulus
instead of Symfony, but little comment here and say stimulus, fetch colon, then
inside single quotes, lazy. What that does is it doesn't it, it tells stimulus not to
download this controller or any of the thing that it imports until an element on the
page appears that matches this controller. So this is going to be downloaded via Ajax
only when it's needed. So check this out. If I go back to my admin, I'll pull up my
network tools here. We'll go to question. Now I'll make this a little bit bigger and
I'll go to edit and then click on JS. So you can see down here, this last one here,
this assets controller, snark down controller, JS.JS. That is actually what
contains our snark down controller. And the key thing is here is the initiator.

What? So I move that load script that tells me that the load script means that this
was loaded after the page is loaded. Once this, uh, text area was visible, it wasn't
included in the main download. You wouldn't see this assets controller snark down
controller in the source code for our page. In fact, if I go to any other page, like
our question list page, you can see that file's not there. It's not there. It doesn't
get downloaded until stimulus sees a matching element that has that controller pretty
cool. All right. Next, it's finally time to do something with our dashboard. Let's
render a chart here and talk about what other things you can do with your main
dashboard page. Okay.

