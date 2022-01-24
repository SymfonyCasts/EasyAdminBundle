# Twig Url

Coming soon...

Check out the detail page of a particular chapter, uh, question actually better.
Let's go look at one that is not approved yet. So we have kind of a lot of buttons up
here, which is probably fine, but what if we don't like their order, like to display
the delete link last, instead of showing in the middle, we can control this, not
surprisingly inside of our configure actions, which is where we are right now. So
I'll go down to the bottom. After we've set up the actions, I'll call another method
on here called reorder and, and past is the page that we wanna reorder them on. So
crowd calling page detail in this case, and then very simply it's just the names of
the actions. So, uh, we have quite a few actions on here. Let's start with our
approved action, then our view action, and then the three built-in actions action,
edit action, colon index, and then finally action delete.

So these, the five actions that correspond with these five button and wanna refresh
very nice. We get them in the same order, by the way, look at these buttons. Most of
them have icons, but these two don't kinda like icons. So let's see if we can fix
the, add an icon to the edit and index button action button across our entire site.
So we're modifying something across the entire site. That means we're doing it inside
a dashboard controller, and we know that we're modifying an action. Now I saw earlier
when we're trying to modify a built in action like this, we can do that by calling
the update function.

So here I'll say->update, and we're gonna update the detail page and the let's start
with the edit action. And we'll pass this a call back. It's gonna receive action
action object as an argument and inside we have to return the action, but we can say
return action,->set icon FA FA dash edit so that as the icon, and then it returns the
action which this function needs to do. And let's do the same thing one more time. So
again, detail page this time, the index action, and we'll give a FA FA dash list and
now beautiful. Those have icons, and we can go anywhere like over to the answer
detail page, and they're gonna have icons there as well.

Now, at this point, we know how to rate a link to any easy admin bundle page. The key
is to get the, I scroll up a little bit, the admin, you were all generator, and then
you can kind of set whatever things you need, like the action and the crud controller
on that. What I'm gonna do now is from the question show page. So let me go to the
homepage and then click into a question. If we are in admin, I wanna put a little
edit button right here. That takes me right to the edit action for this specific
question. So how do we generate anywhere URL to easy admin from twig?

Well, first let's go add that at a button. So the template for this lives at
templates, question show HTL twig, and let's find the H one. There it is. And for
organization, I'm gonna add a little wrap here. So class = de justify content
between, and I'm just gonna put the H one inside of there and now it will let me, now
I can put a link. We can say if is granted roll admin. And if inside an anchor, I'll
leave the empty for a second. Let's say text dash white for the class, instead of
that, a span with class = Fafa dash edit. So just a little edit link here.

We try that. Awesome. Looks good. We have a little edit link there now to generate to
you where else, since we need to set the credit controller, we need to set the action
and we need to set the T ID. Easy admin gives us a nice little, uh, shortcut in twig.
It's called EA_URL. What that does is actually creates, gives you that admin URL
generator object. And then you can just call things on like normal. So we can say dot
set controller, and then, then we can target our controller. So app //controller
//admin //question crud controller. So you can see, I have to do double slashes here
so that they don't get escaped because we're inside of a

String kind of annoying, then that set action edit. Then that set entity ID. We can
pass this question.id. So a little weird to write this kind of code in twig, but
that's how you do it. And it gets the job done, right? Let's refresh over here and,
and let's see hit, edit, and got it right straight to the edit page for our question.
Next last topic. Let's talk about how we can leverage layout panels and other things
to organize our form into different groups, rows, or even tabs on this form page.
Okay.

