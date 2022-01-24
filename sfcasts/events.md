# Events

Coming soon...

One more way to add some custom behavior to our code instead of overriding parent
methods in our controllers is events. So check this out over on our question section.
So question crowd controller up and configure field. Here we go. Let's return one
more field. I'm gonna say yield association field <affirmative> new updated by. So
this is a field on my question entity, which is a many to one to user. And the idea
is that whenever somebody updates a question, this field will be set to the user that
last, that, that just updated it. I'm gonna have this show up only on the detail
page.

So right now I didn't in my fixtures. I didn't set that field. So if I go to any
question you're gonna see updated by no, our goal is to update this field
automatically to set that field automatically. Whenever a question is updated now, a
great solution for this is to use the doctrine extensions library, and it's
believable. This is a way where you can update a field to the last user that modified
it across your entire application. But let's see if we can achieve this just for
inside our easy admin section via events. SOEA Yemen has a bunch of events that it
dispatches and the best way to find them is to go into the source code and open the
event directory. So most of these are pretty self explanatory. Um, before crud action
is called before any crud action is executed after it would be at the end of it. You
also have a bunch of things related to entities here, like before entity updated or
before entity persistent or persisted means created.

So in our case, the one I'm looking at is before entity updated. If we could run code
whenever before an entity is updated, we can set this updated by field and then it
will save naturally. So let's do that. I'm actually gonna open up that before entity
updated event field, and I'm gonna copy its namespace and then go over and we'll,
let's generate the entity. So we can do that with Symfony consult, make subscriber,
and let's call it believable subscriber. It's gonna ask us which event we want to
listen to. It's gonna suggest a bunch of events, um, just from the core of Symfony,
one from easy, Adam, bundle's not gonna be there, so I'm gonna paste it. It's
namespace, then go grab its class name. There we go. And perfect. We have a new
liable subscriber class, so let's go open that up. So source event subscriber,
blendable subscriber. This is a normal Symfony event subscriber, thanks to auto
configuration. It's instantly going to see this class and it's already set up so that
whenever easy admin dispatches this before entity updated events, it's going to call
our method right here.

And this event object is packed with information that's useful for us. So for
example, if I just say event arrow, you can see it has a one method on it called get
entity instance, which is exactly what we want now to be able to set the update of
that property on our question, we're going to need the, the current user object. So
we get that via the security service. So let's auto wire that I'll add_underscore
instruct with a security argument. Then I'll hit alt enter and go to a new properties
to create that property and set it. All right. Beautiful. Let's start with question =
event->GI entity instance, And just as a little sanity check here and to help her
editor, I'll say, if not, question is an instance of question, then I'll throw a, oh,
then I will return because this is gonna be called whenever any entity is saved
across our entire system. Next I'll say user = this->Security arrow, get user and do
a little sanity check there. If not user is an instance of our user, then I'll throw
an exception. So throw new Logic exception. The exception class doesn't matter.

And this is a situation that will never actually happen. We only have one user class
in our app. So if you're logged in, you're definitely this user instance, but this
helps our editor and static analysis tools know confidently that this user object is
going to be our user object down here. We can now say question arrow, set, updated
by, and then user. And my editor's gonna be happy with that. Knowing that this user
is my user entity. All right, let's try it. I have one here. My updated by is Nole.
I'm gonna edit something, make sure you actually make a change so that it saves I'll
have save changes and got it updated by is populated. And that is my current user
sweet.

So evens are a super powerful concept and an easy admin. However, they're a little
bit less important in easy add and bundle three and four than they used to be. And
that's because most of our configuration is now written in PHP in our controller. So
instead of leveraging events, there is an easier way we can just override a method in
our controller. Now, event subscribers still have their place, cuz this is a great
way to do some operation on multiple entities in your system. But if you only need to
do something on one entity, it's easier just to override a method inside that
entity's controller.

So it doesn't matter where, but I'm gonna go to the bottom. We're going to override
yet another method. So the, the methods that we can override are just a, almost a
read me in all the different ways that you can modify things. So there's a create
entity method, a create edit form method. Um, the one that we want is going to be
called update entity. This is a method that actually updates and saves the entity. So
before we update the entity, we wanna set our set the property. So I'm gonna go steal
this code from our subscriber. Actually, I'm gonna close that event. Class, face that
in. I'll hit. Okay. To add that use statement and then let's tweak some code, just
user = this here, get user. And then question is actually going to be instant
instance.

So we can say entity instance,->set updated by, okay. I'm kind of skipping here, but
if you wanted to code defensively, you because the, you can see there's no type on
entity instance here. We could actually do another check like this and say, if the
entity instance is not an instance of question, you could throw an exception, but in
practice it always will be. So we can call these set up date by method on it. All
right. See if this works, I'm gonna go into my subscriber and comment out my
listener. So the subscriber's still here, but it's not going to do anything anymore.
Then go back to questions. Add a different question actually, before I edit it, let's
go look at the details to make sure that there's no updated by perfect edit, make a
change, save changes and beautiful. That works. All right. Next. Let's do a little
bit more with our admin menu over here, like adding sections so we can organize this
a bit better. Okay.

