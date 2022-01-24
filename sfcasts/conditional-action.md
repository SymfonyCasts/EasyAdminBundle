# Conditional Action

Coming soon...

Okay, new goal. This page lists all of the questions on our site and pending approval
lists. Only those questions that are not approved. So ID 24 is not approved yet. We
can see that ID also on the main, main page list. So this question right here is not
approved. I want to, at the end of each row, you can see that every question has a
delete link. I wanna change it. So that only non-approved questions can be deleted.
So I can delete ID 24, but I could not delete ID 13 because it is a, an approved
question. So how do we do that? Since we're talking about questions, let's go to
question crud controller. The most obvious place is configure actions. After all,
this is where we configure what actions our Cru has and what links appear on which
page and what permissions you have.

In fact, you can even call disable and pass an action name, to completely disable an
action for your crud, but that's not what we wanna do here. We don't wanna disable
the delete action everywhere. We just wanna delete it for some of our questions. So
to figure out how to do that, we need to talk more about these, this actions and
action object. So the actions object is basically a container that says which actions
should be on which page. So it knows that on our index page, we wanna have a detail
action, edit action and delete action in this object is created actually in our
dashboard controller. So dashboard controller, it also has a configure actions. And
if I jump into its parent method, you can see that it creates the actions and it sets
up all the default actions for us. So the index page is gonna have new edit, delete.

The detail page is gonna have edit index and edit index delete. And then we also
added the delete action to the index page. Now I noticed when we do this, we, when we
use the ad function here or when our parent controller uses the ad function, it's
just dealing with strings. It's just basically passing the string index and then it's
passing these string edit. But really when we do this, when we pass the string, edit
here behind the scenes, easy admin creates an action object, which knows everything
about how that action should look including its label, CSS classes and other stuff.
So really this actions, object is a collection of information about each page and the
action objects they have on them. And if you did have an action object, I'll jump
into that class. As I mentioned, there's all kinds of things that you can enable that
you can, uh, configure on, like it's label it's icon and other stuff. E even has a
method in here called display. If where we can dynamically control whether or not
this action is displayed. The problem is that from inside of configured actions, what
I kind of wanna do is be able to almost like get the action for a page, like get crud
page index actions,

Lee. I almost wanna be able to fetch that action object and then call display if on
it, but this doesn't work. There's no way for me to access the action object that
represents the delete action on the index page. So that kind at first, that sort of
means that the built in actions that are made inside of our dashboard controller are
things that we can't really modify. I can't really change the, uh, a CSS class or an
icon on the built in action for the index page, but that's not true. We can thanks to
a nice function called update. So here I can say update, pass it, crud page index,
and then action delete, and then pass it a callback. That's going to receive an
action argument. Perfect. So this now means that for the delete action on the index
page, after that's created, I want you to pass it to me so I can make changes to it.
So for now, I'm just going to DD action.

And if we go refresh, we'll see that dump on this page. Perfect. So you can see
inside of there, it's the action object we expected, which has another action DTO
inside of its where all the data is really held. Now we can use the normal method I
want to, which is action, arrow, display, if, and pass it a function, I'll make a
static function. I could have also made this a static function, just being lazy.
Doesn't really matter. That's going to be past the question object. That's just how
that method works. So the purpose of the display, if is that each time that the
action is about to be displayed like next to the first question or the second
question or the third question it's going to call our function, passes that question,
and we can decide whether or not that action should be shown. So that's perfect. We
can say display this. If the question is not

Get is approved, all right, let's try it. Refresh and air. Get as DTO call the member
function, get as DTO on no. Oh, I always do this. The update method. You actually
need to return the action. There we go. Much better. All right. So if you look over
here, look, action is gone, but going down to ID 24, that's not approved it's there.
That is awesome. But this probably isn't quite good enough. We're just hiding the
link. So if we really wanted this to work, we would probably also need to repeat the
same thing on our details page. Right? Cause our details page has a delete link on
it. So I would need the same logic here to update the delete action on page detail.
But even that wouldn't quite be enough, cuz it would still allow someone to somehow
get the URL to the delete and hit the delete. The delete action itself is not, is not
secured. It doesn't truly disable the action. So to be extra safe, we can do so. Give
us to give us that extra layer of security We can check to right before an entity is
deleted. We can check to see if it's approved and if it is, we'll throw an exception.

So real quick, I'm actually going to temporarily comment out this logic and return.
True. And then go back to the question page and perfect delete is now showing up on
everything now to test that security check idea, Go to the bottom of question, crowd
controller. And before we override update entity, this time we're gonna override
delete entity, which is gonna allow us to call code right before an entity is deleted
and to help all my entity I'll document that entity instances is gonna be a question.
An instance of question. Now, if entity instance->is get is approved, then let's just
throw a new exception. Deleting questions is forbidden. This is gonna look like a 500
air to the user. You could also throw an access denied. Doesn't really matter. This
isn't a situation that anyone should have unless we have a bug in our code or some
users trying to do something that they shouldn't be able to do it. So this works, you
can't delete an entity. And as long as we have all the actions configured, so that
index and delete and details, don't have the delete action. Then this should work.

But if we could truly disable the delete action conditionally on an entity by entity
basis, then everything would work a lot simpler, easy admin would know not to
generate the delete links in the right situations. And even if you guessed the UR
that wouldn't work earlier, how can we do that? We're going to need an event listener
and some easy admin internals. That's next?

Yeah.

Let's see. So the trick now is to use this information here, and there's a lot of
information to get our job done <affirmative> which will be to modify this action
config, to disable the delete action under just the right situation. So back over in
our listener, it's gonna look like this. The first thing we need do is get that admin
context. Now I'm gonna kind of set a variable and do an if statement all at once. So
if admin context = event arrow, get admin context, then return from doing error is
coding defensively. It's probably not necessary, but technically this GI admin
context method might not have an admin context. I'm not even sure if that's possible,
but I'm coding defensively just in case. And then we're going to get the crud DTO
from this. So I'm gonna say if, do the same thing, if not crud, DTO = admin context,
arrow, get crud. Then also return once again, this is apparently theoretically
possible, but not going to happen as far as I know in any real situation.

And then remember we only want to perform these changes when we're dealing with the
question class. So that Cru DTO has a way for us to check what entity we're operating
on. So if Cru DTO equal->get entity fully equaled, fully equal class name does not
equal question call on calling class. Then we can also return so relatively
straightforward, but it took me a little bit of digging to find just the right way to
get this information. Now we can get to kind of the, the meat of things. What we
wanna do is first thing we wanna do is actually disable the delete action entirely.
If a question is approved so we can actually get the entity instance by saying
question = admin context, arrow, get entity arrow, get instance, this get entity
actually gives you an entity DTO. And then you can get the instance off of that.

Then below this in an if statement, I'm gonna do something a little weird here at
first. I say, if question is an instance of question, I'll explain why I'm doing that
in a second. And question->get is approved. Then we want to disable the action which
we can actually do by saying CREB, DTO arrow, get actions, config, which gives us an
actions DTO, and then->disable actions, and then pass it an array of action co
delete. So a couple of things here that I want to, the first thing is that this event
is gonna be called at the beginning of every crud action. And so if you're on a crud
action, like edit or delete or details, then question is going to be a question
intact, uh, instance. But if you're on the index page, then you're not operating on
a, that page is not operating on a single entity.

So in that case, there is actually gonna be no entity instance in question will be
no. So by qu checking for question, being an instance of question here, I'm basically
checking to make sure that question isn't Noll. I'm doing instance of question here
as a also because that's gonna help my editor know over here that I can call this GI
is approved method. So I'm checking to make sure that there is a question, but also
kind of hinting to my editor at the same time. The other thing I wanna mention is
that at this point, when you're working with easy admin, you're working with a lot of
D T O objects. We've talked about this a little bit inside of our stroller. We're
often dealing with these nice a, uh, objects like actions or filters, but behind the
scenes, these are just helper objects that ultimately configure a DTO class.

So in the case of actions internally, it is really configuring an action configured,
DT. Anytime we call method on it, it is actually I jump around making changes to the
DTO. And if we look down here on the filters class, you'd see the same thing. So by
the time you get to this part of easy admin, you're dealing with those DTO objects
and they hold all the same data as these we're used to working with, but you're gonna
have different methods for interacting with them. So in this case, if you dig a
little bit, this get actions, config gives you that actions, config, DTO, and it has
a method on it called disabled actions. So I'm gonna put a comment above this says
disabled action entirely For delete

Detail and edit actions. And this is what I talked about earlier. Like if I am on the
detail, actually I try to say page there, if I'm on the detail page or the edit page
or the delete page, um, then we are gonna have a question instance and we can disable
that action entirely, but this isn't gonna disable the links on the index page. So
I'm actually gonna refresh this right here and all of these here are approved. So I
should not be able to delete them if I go and hit, delete on ID 19.

Perfect. You can see it. Get, stop says you do not have enough permissions to run the
delete action or the delete action. Hasn't been disabled. That is thanks to us,
disabling it right here. And also if I go and go, go to the show page, you'll notice
that the delete action is gone. That's once again, thanks to our code on the details
page. We're disabling the delete action. So it's not showing the delete link. If I
clicked one down here like ID 24, that is not approved, you can see that it does have
a delete button. So the only thing we still need to do is hi, is disable or hide this
delete link on the index page to do that. We can say actions = Cru D to arrow, get
action config, just like we did before and then->get actions. But this is gonna give
us Is an array of action DTO that will be Enabled for this page. So if this is the
index page, for example, then it's gonna have a delete action in there. So I'm
actually gonna check for that. I'm gonna say, if not actions, if not

Delete action = actions, let's go rack at action. Delete.

Then I'll put a little, uh, question, question. No, in case that's not set already,
then we are just going to return. So I'm doing here is actually grabbing the delete
action DTO off of the array. If it exists, if it doesn't exist, it means the action
delete action already. Isn't on this page. So it's not a problem, but if we do have a
delete action, then we can say set display. Cullable. This is a great example of the
difference between how code looks on these GTOs and how it looks in our controllers
and our controllers. We can call a, uh, on the nice action object. We can call
action->display. If inside of here with this action DTO, you can do the same thing,
but it's called set display cullable.

So here I'm gonna pass a callable with a question argument, and we're gonna return
that it is dis uh, can be displayed if not, question->get is approved. All right,
let's try that. The only thing that wasn't working before is this delete action needs
to be hidden on the index page. And now it is, it is gone for all of them, except if
I go down and find one of my, the high IDs are the ones that are not approved. Yes,
we have a delete action there. All right, next, let's add a custom action. We're
gonna start simple custom action link. That takes us to the front end of this site.
When we're viewing a specific item, then we're gonna get more complicated.

