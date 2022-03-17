# Extending with Events

So far, we've added behavior to our code by overriding methods in our controllers.
And that is a *great* approach, and will be what you should use in most cases.
But there *is* another possibility: events.

Over in `QuestionCrudController`, up in `configureFields()`... let's return one more
field: `yield AssociationField::new('updatedBy')`.

This field - that lives on the `Question` entity - is a `ManyToOne` to `User`.
The idea is that, whenever someone updates a `Question`, this field will be set to
the `User` object that just updated it. Let's make this *only* show up on the detail
page: `->onlyOnDetail()`.

[[[ code('c82afa8938') ]]]

Right now, in our fixtures, we are *not* setting that field. So if we go to any
question... it says "Updated By", "Null". Our goal is to set that field automatically
when a question is updated.

A *great* solution for this would be to use the doctrine extensions library and
its "blameable" feature. Then, no matter *where* this entity is updated - inside
the admin or not - the field would automatically be set to whoever is logged in.

## Discovering the Events

But let's see if we can achieve this *just* inside our EasyAdmin section via
events. EasyAdmin has a bunch of events that it dispatches and the best way to find
them is to go into the source code. In EasyAdmin's vendor code, open the `src/Event/`
directory. Most of these are... pretty self explanatory! `BeforeCrudAction` is
dispatched at the start when *any* CRUD action is executed, "after" would be at
the end of that action.. and we also have a bunch of things related to entities,
like `BeforeEntityUpdatedEvent` or `BeforeEntityPersistedEvent`, where "persisted"
means "created".

For our case, the one I'm looking at is `BeforeEntityUpdatedEvent`. If we could
run code *before* an entity is updated, we could set this `updatedBy` field and then
let it save naturally. Let's do that.

## Creating The Event Subscriber

Open up `BeforeEntityUpdatedEvent` and copy its namespace. Then, over on our terminal,
run:

```terminal
symfony console make:subscriber
```

Let's call it `BlameableSubscriber`. It then asks us which event we want to listen
to, and it suggests a bunch from the core of Symfony. The one from EasyAdminBundle
won't be here, so, instead, I'll paste its namespace, *then* go grab its class name...
and paste that too.

And... perfect! We have a new `BlameableSubscriber` class! Go open that
up: `src/EventSubscriber/BlameableSubscriber.php`.

[[[ code('4bd0d51381') ]]]

This is a normal Symfony event subscriber and, thanks to auto configuration,
Symfony will instantly see this and start using it. In other words, whenever EasyAdmin
dispatches `BeforeEntityUpdatedEvent`, it will call our method.

This `$event` object is *packed* with useful info. For example, if I just say
`$event->`, one method is called `getEntityInstance()`, which is *exactly* what we
want.

To be able to set the `updatedBy` property on our question, we're going to need the
current user object, which we get via the security service. Let's autowire that:
add `public function __construct()` - with a `Security $security` argument. Hit
"alt" + "enter" and go "Initialize properties" to create that property and set it.

[[[ code('de83b152eb') ]]]

Love it. Below, start with `$question = $event->getEntityInstance()`. And then
`if (!$question instanceof Question)`, just `return`... because this is going to
be called when *every* entity is saved across our entire system. Next,
`$user = $this->security->getUser()` and `if (!$user instanceof User)`, let's
throw a new: `LogicException()`... the exception class doesn't matter. This is a
situation that will never *actually* happen: we only have one `User` class in our
app. So if you're logged in, you will *definitely* have this `User` instance. *But*,
this helps our editor and static analysis tools.

[[[ code('19ba984cdd') ]]]

Down here... we can now say `$question->setUpdatedBy()`, and pass `$user`.

[[[ code('e12bb3f9b9') ]]]

Let's try it. This question's "Updated By" is "Null". Edit something (make sure you
actually make a change so it saves), hit "Save changes" and... got it! "Updated
By" is populated! And *that* is my current user. Sweet!

## Alternative: Overriding a Method

So events are a powerful concept in EasyAdmin. However, they're a little bit less
important in EasyAdmin 3 and 4 than they used to be. And that's because most
of our configuration is now written in PHP in our controller. So instead of leveraging
events, there's often an easier way: we can just override a method in our controller.

Event subscribers *still* have their place, because they are a great way to do an
operation on *multiple* entities in your system. But if you only need to do something
on *one* entity... it's easier to override a method inside that entity's controller.

Let's try it. I'll got to the bottom of my controllerclass and override yet *another*
method. The methods that we can override are almost a README of all the different ways
that you can extend things. There's a `createEntity()` method, a `createEditForm()`
method, and the one we want is called `updateEntity()`. This is the method that
actually updates and saves the entity. *Before* that happens, we want to set the
property.

[[[ code('e053f848c3') ]]]

Go steal the code from our subscriber... close that event class... paste that in...
and hit "OK" to add that use statement. And now we'll tweak some code: `$user
= $this->getUser()`... and then `$question` is actually going to be `$entityInstance`.
So we can say `$entityInstance->setUpdatedBy()`.

[[[ code('cc9e127bda') ]]]

If you want to code defensively, since there's no type-hint on `$entityInstance`,
we could do another check where we say `if (!$entityInstance instanceof Question)`
then throw an exception. But in practice, this *will* always be a `Question`
object.

Ok: let's see if this works. Go into `BlameableSubscriber`... and comment out the
listener. The subscriber is still here, but it won't *do* anything anymore. Then
go back to Questions... and edit a different question. Actually, before I do that,
go look at the details to make sure there's no "Updated By". Perfect! Now edit,
make a change, save your changes, and... it still works!

Next, let's do a little bit more with our admin menu, like adding sections
to make this whole thing better organized.
