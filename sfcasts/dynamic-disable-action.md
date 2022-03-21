# Dynamic Disable an Action & AdminContext

We've done a good job of hiding the `DELETE` action conditionally and disallowing
deletes using that same condition. But it would be *much* simpler if we could truly
*disable* the `DELETE` action on an entity-by-entity basis. Then EasyAdmin would
naturally just... hide the "Delete" link.

## The AdminContext Object

To figure out how to do this, let's click into our base class -
`AbstractCrudController` - and go down to where the controller methods are. Check
this out: in every controller method - like `index()`, `detail()`, or `delete()` -
we're passed something called an `AdminContext`. This is a configuration object that
holds *everything* about your admin section, *including* information about which
EasyAdmin *actions* should be enabled. So, by the time our controller method
has been called, our EasyAdmin *actions* config has already been used to
populate details inside of this `AdminContext`.

And look what happens immediately inside the method: it dispatches an event! I
wonder if we could hook into this event and *change* the action config - like
conditionally disabling the `DELETE` action - before the rest of the method
runs and the template renders.

## Creating the Event Subscriber

Let's try that! Scroll up to `BeforeCrudActionEvent` - let me search for
that... there we go - and copy it. Spin over to your terminal and run:

```terminal
symfony console make:subscriber
```

Let's call it `HideActionSubscriber`... and then paste the long event class.
Beautiful! Let's go see what that subscriber looks like. It looks...
pretty familiar! Let's `dd($event)` to get started.

When we refresh... it immediately hits that because this event is dispatched *before*
every single CRUD action.

## Working with AdminContext

The hardest part of figuring out how to dynamically disable the action is just...
figuring out where all the data is. As you can see, we have the `AdminContext`.
*Inside* the `AdminContext`, among other things, is something called a `CrudDto`.
Inside the `CrudDto`, we have an `ActionConfigDto`. *This* holds information about
all the actions, including "index" (the current page name), and all the action
config. This shows us, for each page, which array of action objects should be
enabled. So for the "edit" page, we have these two `ActionDto` objects, and each
`ActionDto` object contains all the information about what that action should
look like. Whew...

So now the trick is to use this information (and there's *a lot* of it) to modify
this config and disable the `DELETE` action in the right situation. Back over
in our listener, the first thing we need to do is get that `AdminContext`. Set a
variable and do an if statement all at once:
`if (!$adminContext = $event->getAdminContext())`, then `return`.

I'm coding defensively. It's probably not necessary... but technically the
`getAdminContext()` method might not return an `AdminContext`. I'm not even sure
if that's possible, but better safe than sorry. Now get the `CrudDto` the same way:
`if (!$crudDto = $adminContext->getCrud())`, then also `return`. Once again, this
is *theoretically* possible... but not going to happen (as far as I know) in any
real situation.

Next, remember that we only want to perform our change when we're dealing with
the `Question` class. The `CrudDto` has a way for us to check which entity we're
dealing with. Say `if ($crudDto->getEntityFqcn() !== Question::class)`, then
`return`.

So... this is *relatively* straightforward, but, to be honest, it took me some
digging to find *just* the right way to get this info.

## Disabling the Action

*Now* we can get to the core of things. The first thing we want to do is *disable*
the delete action entirely if a question is approved. We can get the entity
instance by saying `$question = $adminContext->getEntity()->getInstance()`. The
`getEntity()` gives us an `EntityDto` object... and then you can get the instance
from that.

Below, we're going to do something a *little* weird at first. Say
`if ($question instanceof Question)` (I'll explain why I'm doing that in a second)
`&& $question->getIsApproved()`, then disable the action by saying
`$crudDto->getActionsConfig()` - which gives us an `ActionsDto` object - then
`->disableActions()` with `[Action::DELETE]`.

There are a few things I want to explain. The first is that this event is going to
be called at the beginning of *every* CRUD page. If you're on a CRUD page like
`EDIT`, `DELETE`, or `DETAIL`, then `$question` *is* going to be a `Question` instance.
*But*, if you're on the index page... that page does *not* operate on a *single*
entity. In that case, `$question` will be null. By checking for `$question` being
an `instanceof Question`, we're basically checking to make sure that `Question`
isn't null. It also helps my editor know, over here, that I can call the
`->getIsApproved()` method.

The other thing I want to mention is that, at this point, when you're working with
EasyAdmin, you're working with a lot of DTO objects. We talked about these earlier.
Inside of our controller, we deal with these nice objects like `Actions` or
`Filters`. But behind the scenes, these are just helper objects that ultimately
configure DTO objects. So in the case of `Actions`, internally, it's *really*
configuring an `ActionConfigDto`. Any time we call a method on `Actions`... it's
actually... if I jump around... making changes to the DTO.

And if we looked down here on the `Filters` class, we'd see the same thing. So by
the time you get to *this* part of EasyAdmin, you're dealing with those DTO objects.
They hold all of the same data as the objects we're used to working with, but
with different methods for interacting with them. In this case, if you dig a
bit, `getActionsConfig()` gives you that `ActionConfigDto` object... and it
has a method on it called `->disabledActions()`. I'll put a comment above this that
says:

```
// disable action entirely for delete, detail & edit pages
```

Yup, if we're on the detail, edit, or delete pages, then we're going to have a
`Question` instance... and we can disable the `DELETE` action entirely.

But this *isn't* going to disable the links on the index page. Watch: if we refresh
that page... all of these are approved, so I should *not* be able to delete them.
If I *click* "Delete" on ID 19... yay! It *does* prevent us:

```
You don't have enough permissions to run the "delete"
action [...] or the "delete" action has been disabled.
```

That's thanks to us disabling it right here. And also, if we go to the detail page,
you'll notice that the "Delete" action is gone. But if we click a Question down here,
like ID 24 that is *not* approved, it *does* have a "Delete" button.

Ok, let's finish by hiding the "Delete" link on the index page. To do that,
add `$actions = $crudDto->getActionConfig()`, just like we did before, and then
`->getActions()`. *This* will give us an *array* of the `ActionDto` objects that
will be enabled for this page. So if this is the index page, for example, then
it will have a "Delete" action in that array. I'm going to check for that:
`if (!$deleteAction = $actions[Action::DELETE])`... and then add `?? null` in case
that key isn't set. If there is *no* delete action for some reason, just `return`.
But if we *do* have a `$deleteAction`, then say `$deleteAction->setDisplayCallable()`.

This is a great example of the difference between how code looks on these DTO
objects and how it looks with the objects in the controllers. There,
on the `Action` object, we can call `$action->displayIf()`. In the event
listener, with this `ActionDto`, you can do the same thing, but it's called
`->setDisplayCallable()`. Pass this a `function()` with a `Question $question`
argument... then we'll say: please display this action link if
`!$question->getIsApproved()`.

Phew! Let's try that! We're looking to see that this "Delete" action link is hidden
from the index page. And now... it *is*! It's gone for all of them, *except*...
if I go down and find one with a higher ID... which is *not* approved... yes!
It *does* have a "Delete" link.

Next, let's add a custom action! We're going to start simple: a custom action link
that takes us to the frontend of the site. Then we'll get *more* complicated.
