# Get Action

Coming soon...

Let's create a totally custom action. How about when we are on the details page for a
question, a new action called view that takes us to the front end for this question.
All right. So let's start in question. Crowd controller, we're adding a new action.
So it's probably gonna be inside a configured actions and we know how to add
different actions to different pages it's with this ad method. So let's just try
adding to crud page detail, a new action called how about view? There are a bunch of
built in action names, as we know, like index delete. And we usually reference those
with the action constant, by this case, we're making a new action. So let's just
invent a string called view and see what happens refresh. And we are greeted with the
nice air. The view action is not a built in action, so you can't add or configure it
via its name. Either refer to one of the built in actions or create a custom action
called view. So we talked about in the last chapters, how behind the scenes, all of
the actions are all the, each action is actually an action object.

Most of the time, we don't need to worry about that, but when we create a custom
action, we actually going to need to create our own custom action object. So I'm
actually gonna do this up above the return. We'll say view action = action, colon,
colon new, and then we'll pass it. That same name that I just invented view. Then
down here, instead of the string, you can see that this argument takes a page name.
If it's a built, sorry, an a name, if it's a built in action or an object, an action
object. So I'm gonna pass in that new view action variable refresh. Now to see
another error actions must link to either a route, a, a crud action or a URL, and
then it gives us three different methods we can use to get that set up. That's a
pretty great error message. It sounds like link to route or link to URL is what we
need.

So up here on the modify, our action, and we could use link to route, but as we kind
of already know from other places in our system, this is going to generate a URL to
the route. But with all the extra admin query parameters, we don't really want that.
So instead let's use link to URL, but the problem is we can't just say this->generate
URL right here, because we need, need to know which question we're generating URL
from. And we don't have that right here. Fortunately, as you see, the argument takes
a string or a callable, Okay, let try that. I'm gonna pass a function here And then
to see what we are past this function, I'll use a little trick. I'll DD funk, get
ARGs Back over my browser. Awesome. We are apparently past a one argument, which is
the question object. That's beautiful because we can use that to return a, to
generate a URL Return, this->generate URL and the name to our name of our front end.
Route name is app question show, and then this needs a slug route wild card set to
The question, slug, which to get that. Let me add the question argument to our method
and say question arrow, get slug.

Awesome. All right. Try it now. And yes, we have a view button. If we click it, it
works. And now just like any other action we can modify it. We can say, how about ad
CSS, class BTN, BTN dash success and set icon Fafa dash I and set label View on site.
All things that we've done before on other actions and refresh the ha looks great.
And if we want to include this action on other pages, we can right now, if you go to
the index page, there's no view on front end action, but we can add it. Cuz we
created this new, nice view, an object. So down here in the bottom, we can just reuse
that. So crud in page index will also pass that same view action object now, refresh
and beautiful. You can see the BTN styling didn't really work well on here. So you
might wanna remove that or clone the action and create two separate things. It's up
to you. Okay. So creating an action that links somewhere is cool. What about true
custom action that connects to a custom controller with custom logic. Next let's add
a custom action that allows moderators to approve questions.

