# Field JavaScript

Coming soon...

Go to the question, edit page. Perfect. So the question's in this text area here,
which is nice. What would be even better if we could have maybe like a fancy editor
here to help with some of our markup. Fortunately easy. Adam has something just for
this. So in question credit controller on our question field, instead of a text area,
changes to text editor, field, refresh the page and we have a nice little editor.
Cute. It's simple, but can still, but it's still really nice. If you look inside of
the text editor field, you can see a little bit of how this works. So it says, it
says, add CSS files and add JS files. So easy admin comes with some JavaScript and
some CSS that, uh, adds this functionality and they can actually include this extra,
the extra CSS and JavaScript file whenever this field is rendered.

But in, in reality, our question field on our site holds markdown. So it would be
really cool. If so, go back to using the text area field. We don't need any fancy. It
would be really cool if we could, as we type markdown inside of here, have a preview
rendered right below this automatically. So let's do that for this to happen. We are
going to need some custom JavaScript to do that markdown rendering so far, if you
open our Webpac dot ENCO Webpac Doig JS file, we do have a custom admin CSS file. Now
we're also going to need a custom admin dot JS file. So up in the asset S directory
right next to our main app dot JS that's included on the front end, create a new
admin dot JS file inside of here. We're gonna do, we're gonna import two things.

First import these styles file dot /style /admin do CSS. And then we're going to
import dot /bootstrap. This file is also imported by our app dot JS on the front end.
And the purpose of it is that it starts the stimulus application and loads, anything
in our controller's directory as stimulus controllers. If you, if you don't know
about, if you haven't used stimulus before, it's not required to do custom
JavaScript, it's the way that I like to do custom JavaScript. It's awesome. And we
have a really big tutorial on it. So the admin JS file reads the CSS file and it also
starts the stimulus starts initializes our stimulus controllers. Now over in web pec,
IIG that JS, we can change this to be a normal entry and point it at do /assets
/admin JS. So the end result of this is it's going to now render a built admin dot JS
file, and also a built admin dot CSS file since we're import CSS from our JavaScript,
because we just made a change to our Webpack and fig file. We need to go over, find
our tabs running Encore, stop that with control C and restart it.

Perfect down here, you can see that our admin entry point is rendering an admin at
CSS and an admin JS file. It's also rendering a couple other files that's for
performance. The important thing is that if you go refresh any page right now and
viewed source on this page, you can see that. And our admin CSS is still being loaded
as well as our admin JavaScript, which is all of this stuff right here. So we now
have the ability to add custom JavaScript to our site. So here's the plan. We are
going to install a JavaScript markdown library, parser called snark down. Then as we
type into this box, it's going to in real time, render a HTML preview below this and
to hook this up, we're gonna write a stimulus controller.

So first let's install that library. So over in my main tab, I'll run yarn, add snark
down dash dev. That's the name of the JavaScript library that par says markdown
beautiful. Next up in assets controllers. I'm gonna create a new St. This controller
called snark down_controller JS. So because I'm calling it, this, the name and
stimulus is going to be snark down. Now I'm just going to paste in some contents. The
what's inside of here is not that important. We're gonna apply this controller to the
row that's around our field. So let me make things a little bit smaller here. So
we're gonna apply the controller to this row. That's around here. We're going to add
a target to the actual text area itself and an action. So the end result of this is
that when this controller is initialized, it's gonna create a little markdown preview
dev. And then whenever we type into the field, it's gonna call render, which is going
to re render the, uh, preview into the box. If you have any, we're not gonna go too
much into this. You have any questions about this control it's you can ask in the
comments,

But thanks to the fact that admin JS is importing bootstrap. That's initializing our
controllers. This snark down controller is already available in our admin section to
connect it to our field.

We're gonna say set form type options past this NRA. And we're gonna add a couple of
attributes. The first thing I wanna add is row_ATTR. This is attributes you wanna add
to the form row. This is not an easy admin thing. This is just a, uh, an option
inside Symfony's form system. Here. We're gonna say data dash controller. This is how
you initialize a stimulus controller, and we're gonna say snark down next, pass an
ATTR option. This is gonna be attributes to it or applied to the text area itself. We
need to here the first one's data dash snark down dash target set to input that adds
a target to our stimulus controller called input, that points to our text area. And
the next one is data action set to snark down name of our controller pound sign
render. What that's gonna do is it's gonna say whenever the text area changes, call
the render method on our snark down controller, which is what's going to update the
markdown preview. All right. So let's try it. I'm gonna go over and refresh and
actually let's do a, let's do a force refresh and Hmm. I don't see anything that's
like in our console here. That looks fine.

Let me make sure that my actually let's inspect element on this debugging time. Oh,
there it is right there. You guys probably saw data. <inaudible> go SAR down. So if
you don't type of that, my controller is never actually initialized snark down.
<affirmative>. Now, when we refresh, there we go. A little more luck. You can see our
preview down there and as I type it instantly updates to make something bold. Awesome
though, we could probably fix the styling here a little bit. So fortunately, we also
know how to add styles to our admin area. So I'll go to our admin dot CSS file and
I'm AOL markdown preview selector here. This is a class that our controller adds to
the preview area. So we're gonna take advantage of that to add a little bit of
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
page appears that matches this controller. So this is gonna be downloaded via Ajax
only when it's needed. So check this out. If I go back to my admin, I'll pull up my
network tools here. We'll go to question. Now I'll make this a little bit bigger and
I'll go to edit and then click on JS. So you can see down here, this last one here,
this assets controller, snark down controller, JS dot JS. That is actually what
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

