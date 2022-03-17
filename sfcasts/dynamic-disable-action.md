# Dynamic Disable Action

We've done a good job of hiding the `DELETE` action conditionally and disallowing deletes under that same condition. But it would be much simpler if we could *truly* disable the `DELETE` action on an entity-by-entity basis. Then EasyAdmin would naturally just hide the "Delete" link. To figure out how to do this, let's click into our base class, `AbstractCrudController.php`, and go down to where our actions are. Check this out! In every action like `index`, `detail`, or `delete`, we're passed something called an `AdminContext`. This is a configuration object that holds everything about your admin section, including information about what actions should be enabled. So by the time our action method has actually been called, our actions config has already been used to create the action objects and we have figured out which actions we want. And look what it does here. It dispatches an event. So I wonder if we can hook into this event and actually change that action config conditionally before the rest of the action runs and the template renders.

All right, I'm going to scroll up to `BeforeCrudActionEvent` - let me search for that... there we go - and I'll copy that. Spin over to your terminal and run:

```terminal
symfony console make:subscriber
```

Let's call it

```terminal
HideActionSubscriber
```

and I'll paste my long event name right there. Beautiful! Let's go see what that subscriber looks like. It should be pretty familiar. We've got the setup, the method, and to start here, let's just `dd($event)`. When we refresh... it gets hit immediately because that event is dispatched *before* every single CRUD action.

The hardest part of figuring out how to dynamically disable this event is just figuring out where all of the data is. As you can see, we have the `AdminContext`. *Inside* the `AdminContext`, among other things, is something called a `CrudDto`. Inside the `CrudDto`, we have an `ActionConfigDto`. This holds information about all the actions, including the index (the current page name), and all of our action config. The action config shows us, for each page, which array of action objects should be enabled. So for the edit page, we have these two `ActionDto` objects, and each `ActionDto` object contains all the information about what that action should look like. Whew...

So now, the trick is to use this information (and there's *a lot* of it) to modify this action config to disable the `DELETE` action in the right situation. Back over in our listener, the first thing we need do is get that `adminContext`. I'll set a variable and do an if statement all at once, so `if (!$adminContext = $event->getAdminContext())`, then `return`. What I'm doing is coding defensively. It's probably not necessary, but technically this `getAdminContext()` method might not have an `adminContext`. I'm not even sure if that's possible, but I'm coding defensively just in case. And then we're going to get the `crudDto`, so I'll do the same thing: `if (!$crudDto = $adminContext->getCrud())`, and also `return`. Once again, this is *theoretically* possible, but not going to happen (as far as I know) in any real situation.

And remember, we only want to perform these changes when we're dealing with the `Question` class. The `crudDto` has a way for us to check what entity we're operating on. Say `if crudDto->getEntityFqcn() !== Question::class)`, and then we can `return`. So... relatively straightforward, but it took me a little bit of digging to find *just* the right way to get this information.

Now we can get to the core of things. The first thing we want to do is *disable* the delete action entirely if a question is approved. We can actually get the entity instance by saying `$question = $adminContext->getEntity()->getInstance()`. This `getEntity()` gives you an `entityDto`, and then you can get the instance from that.

Then below this, we're going to do something a *little* weird at first. Say `if ($question instanceof Question)` (I'll explain why I'm doing that in a second) `&& $question->getIsApproved()`. Then we want to disable the action by saying `$crudDto->getActionsConfig()`, which gives us an `actionsDto`, and then `->disableActions()`, with `[Action::DELETE]`.

There are couple of things here that I want to explain. The first is that this event is going to be called at the beginning of every CRUD action. So if you're on a CRUD action, like `EDIT`, `DELETE`, or `DETAILS`, then `$question` *is* going to be a question instance. *But*, if you're on the index page, then that page is not operating on a single entity. In that case, there will actuually be *no* entity instance and `$question` will be null. By checking for `$question` being an `instanceof Question` here, we're basically checking to make sure that `Question` isn't null. It will also help my editor know, over here, that I can call this `->getIsApproved()` method. 

The other thing I want to mention is that, at this point, when you're working with EasyAdmin, you're working with a lot of DTO objects. We've talked about this a little bit before. Inside of our controller, we're often dealing with these nice objects like `Actions` or `Filters`, but behind the scenes, these are just helper objects that ultimately configure a DTO class. So in the case of `Actions`, internally, it is really configuring an `ActionConfigDto()`. Any time we call method on it, it is actually... if I jump around... making changes to the DTO. And if we look down here on the `Filters` class, you'd see the same thing. So by the time you get to this part of EasyAdmin, you're dealing with those DTO objects and they hold all of the same data as these objects we're used to working with, but you'll have to use different methods for interacting with them. In this case, if you dig a little bit, this `getActionsConfig()` gives you that `ActionConfigDto()`, and it has a method on it called `->disabledActions()`. I'll put a comment above this that says:

```
// disable action entirely for delete, detail & edit page
```

This is what I talked about earlier. If I'm on the detail, edit, or delete pages, then we're going to have a `$question` instance and we can disable that action entirely. But this *isn't* going to disable the links on the index page. So I'll actually refresh this page here and... all of these are approved, so I should *not* be able to delete them. If I go and hit "Delete" on ID 19... perfect! It says:

```
You don't have enough permissions to run the "delete"
action [...] or the "delete" action has been disabled.
```

That is thanks to us disabling it right here. And also, if I go to the show page, you'll notice that the "Delete" action is gone. That's, once again, thanks to our code on the details page. We're disabling the "Delete" action, so it's not showing the "Delete" link. If I clicked one down here, like ID 24 that is *not* approved, you can see that it *does* have a "Delete" button.

The only thing we still need to do is hide this "Delete" link on the index page. To do that, we can say `$actions = $crudDto->getActionConfig()`, just like we did before, and then `->getActions()`. What this is going to give us is an array of `ActionDto` that will be enabled for this page. So if this is the index page, for example, then it's going to have a "Delete" action. I'm actually going to check for that. I'll say `if (!$deleteAction = $actions[Action::DELETE])`, and then I'l add `?? null` in case that's not set already. Then we're just going to `return`. What I'm doing here is actually grabbing the `$deleteAction` DTO from the array if it exists. If it *doesn't* exist, it means the `$deleteAction` isn't on this page already, so it's not a problem. But if we *do* have a `$deleteAction`, then we can say `$deleteAction->setDisplayCallable()`. This is a great example of the difference between how code looks on these DTOs and how it looks in our controllers. In our controllers, on the `Action` object, we can call `$action->displayIf()`. Inside of *here*, with this action DTO, you can do the same thing, but it's called `->setDisplayCallable()`.

Here, I'm going to pass a callable, `function()` with a `Question, $question` argument. Then, we'll `return !$question->getIsApproved()`. All right, let's try that. The only thing that wasn't working before is that this "Delete" action needs to be hidden on the index page. And now... it *is*. It's gone for all of them, *except* if I go down and find one of the higher IDs, which are not currently approved. And... yes! We have a "Delete" action there.

Next, let's add a custom action. We're going to start simple: A custom action link that takes us to the frontend of this site, when we're viewing a specific item. Then we'll get *more* complicated.
