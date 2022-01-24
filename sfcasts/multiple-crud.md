# Multiple Crud

Coming soon...

Right now we have one crud controller per entity, but we can create more than one
controller crud controller for the same entity. It's useful. If you need to set up
some filters behind the scenes in always apply them. For example, we are going to
create a separate pending approval question section that only lists questions that
need to be approved. All right. So to do this, we're gonna create a new crowd
controller instead of generating it this time, I'm gonna create it by hand. So I'll
call question pending approval crud controller. And the reason I'm creating it by
hand is instead of having an extended normal, uh, base class for a crowd controller,
I'm gonna have an extended question, crowd controller, that way it inherits all of
the normal question, crowd controller syntax. And actually that was, try that again.
Cresent crowd controller, not normal question controller. Now, whenever we have a new
crowd controller, we need to link to it from our dashboard. So I'll open the
dashboard And then I'll kind of, I'll duplicate our questions one here, And I'll say
pending approval. I'll tweak the icon slightly. Now, if we stop now, you might be
wondering since both of these simply point to question in entity, how do they know to
go to the correct controller? And that actually is a problem. As soon as you have
multiple correct controllers for the same entity, easy admin is just going to guess
the correct one to use, to tell it explicitly, you can say set CR controller and then
pass that question. Pending approval controller, call on call on class.

Do we need to set the controller on this other one to be safe? Absolutely. And we'll
do that in a few minutes. All right. So let's try it though. Refresh, we get two
links and they look absolutely identical, which makes sense. All right. So now let's
modify a query for this one to only show non-approved Questions. So we know how to do
that over in our new controller, I'm going to override a method Called create index
query builder. And then I'll just modify this. We'll say->and where we know that our
entity alias is called entity. So entity.is approved As the field on our question.
Entity = colon approved and then set for it approved set to false. Now, if we try it,
we see we have a bunch of these right now. We go from a bunch to just five. It works
Except if you go to the original question section that also only shows five, as I
mentioned, it's guessing the wrong crud controller. So in REA in practice, as soon as
you have multiple crud controllers for entity, you should always specify the
controller when you link to it. This one I'll use question, crud controller class.
There we go. And if I refresh this page, that won't make any difference. What we've
modified was the link. So if I click the link, that will take me to the correct admin
section.

All right. Let's tweak a couple things on our new crud controller. So I'm going to
override, I'm going to override Configure crud. Most importantly, just set a Page
title. I'm gonna set up for the index page. So I'll say crud, call on call on page
index. I'll say questions pending approval.

<affirmative>

Now that's much more obvious what's happening here. And when we're setting the page
title, we can actually pass a callback. If we want to make, want to use the question,
an object itself in the name. So let's actually try this for the let's call set page
title again, and try this for the details page. So crowd calling page detail, then
instead of a string here, I'm going to pass a call back. So I'll say static function,
and that will receive the question object as the first argument. Then inside we can
do return, whatever we want. So I'll return sprint F and let's kind of put a little
pound sign percent at S present S in pass, question, arrow, get ID and question
arrow, get name nice. So let's try that head over to the show page for one of these
things and awesome. We control a title with that dynamic data. And while we're here,
the last thing I'm gonna do is add a little Help, little help message on a specific
page. So I'm gonna add it to my index page in case somebody comes here and doesn't
know what they're looking at. Um, I'll say questions are not published to users until
approved by a moderator.

And that little message. If we refresh shows up night, right year next to the title,
all right, there is one more subtle problem that having these two crud controllers
has just created to see that problem. I want you to jump into answer crud controller.
And one of the fields we have here is a, the association field to question, I'm gonna
change this to auto complete, which it probably should have since there's going to be
a lot of questions in my database. All right. So if I look at my main question page
here looks like

<affirmative>.

Yeah, right. I'm going to undo that swing back over so we can clear it out. So if we
go to our main questions page here, probably this first one, I'm just gonna guess
since most of these questions are approved this first, one's probably an approved
question. Now go to answers. Let's edit an answer, go down to the questions here. And
now this uses auto complete, which is cool. But if I pace that string, it says no
results found. The reason is subtle. If you go down to the web, give our toolbar and
open the profiler for one of those auto complete HX requests. Look at the URL
closely. Check this out. Part of the URL actually says crud controller = question
pending approval crud controller.

When an auto complete ax request is done for an entity, like in this case, it's
trying to auto complete questions that ax request is done by a crud controller. There
is actually, if you jump into abstract crud controller, there's actually an auto
complete action. This is the action that's called to create that, uh, response, the
auto complete response. This is done this way so that the autocomplete results can
reuse your index query builder for the results. Unfortunately, just like with our
dashboard right now, the auto complete is guessing the wrong crud controller. It's
going to the question pending approval crowd controller. So to fix this once again,
we just need to be explicit. We can say set crud controller. And then we say question
crud controller on class this time I'll refresh, go down in question, search my
string and it finds it. All right. Next. What if we wanna run some code before or
after an entity is updated or created or deleted? Easy. Admin has two solutions,
events and controller methods.

