# Conditionally Disabling an Action

Okay, new goal. This page lists *all* of the questions on our site.. while the
Pending Approval page lists only *not* approved questions. So ID 24 is *not*
approved. We can see this same item on the main Questions page... and at the end
of each row, there's a link to delete that question.

I want to change this so that only *non-approved* questions can be deleted.
For instance, we *should* be able to delete question 24, but *not* question 13
because it's an *approved* question. How can we do that?

Since we're talking about questions, let's go to `QuestionCrudController`. The
most obvious place is `configureActions()`. After all, this is where we configure
which actions our CRUD has, which action links appear on which page, and what
permissions each has. We can even call `->disable()` and pass an action name to
completely disable an action for this CRUD.

# Actions and Action Objects

*But*, that's *not* what we want to do here. We don't want to disable the "delete"
action *everywhere*, we just want to disable it for *some* of our questions. To figure
out how to do that, we need to talk more about the `Actions` and `Action` classes.

The `Actions` class is basically a container that says which actions should be on
which page. So it knows that on our index page, we want to have a "show" or
"detail" action, "Edit" action, and "Delete" action.

This `Actions` object is actually created in `DashboardController`. It *also* has
a `configureActions()` method. And if we jump into the parent method, yup! *This*
is where it creates the `Actions` object and sets up all the *default* actions for
each page. So `PAGE_INDEX` will have `NEW`, `EDIT`, and `DELETE` actions... and
`PAGE_DETAIL` will have `EDIT`, `INDEX`, and `DELETE`. *We* also added the
`DETAIL` action to `PAGE_INDEX`.

Notice that when we use the `->add()` method - or when our parent controller uses
it - we pass a *string* for the action name. `Action::EDIT` is a just constant that
resolves to the string "edit".

But, behind the scenes, EasyAdmin creates an `Action` *object* to represent this.
And that `Action` object knows *everything* about how that action should look,
including its label, CSS classes, and other stuff. So really, this `Actions` object
is a collection of the `Action` *objects* that should be displayed on each page.

And if you *did* find yourself with an `Action` object - I'll jump into that class -
there would be all kinds of things that you could configure on it, like its label,
icon, and more. It even has a method called `displayIf()` where we can dynamically
control whether or not this action is displayed.

So... great! We could use that to conditionally hide or show the delete link!
Yep! Except that... inside of `configureActions()`, to do that, we need a way
to *get* the `Action` object for a specific action... like "give me the `Action`
object for the "delete" action on the "index" page. Then we could call
`->displayIf()` on that.

But... this doesn't work. There's no way for us to access the `Action` object
that represents the `DELETE` action on the `PAGE_INDEX`. So... does this mean
that the built-in actions added by `DashboardController` can't be changed?

Thankfully, no! We *can* tweak these `Action` objects thanks to a nice
function called `->update()`. Say `->update(Crud::PAGE_INDEX, Action::DELETE)`,
and then pass a callback that will receive an `Action` argument.

## Using Actions::displayIf()

Perfect! This now means that, after the `DELETE` action object is created for
`PAGE_INDEX`, it will be passed to *us* so we can make changes. For now, just
`dd($action)`.

[[[ code('86272b9681') ]]]

If we refresh... yup! It dumped the `Action` object, as expected... which has an
`ActionDto` object inside... where all the data is really held.

Back in the callback, add `$action->displayIf()` and pass this another callback:
a `static function()` that will receive a `Question $question` argument. Now,
*each* time the `DELETE` action is about to be displayed on the index page - like
for the first, second then third question, etc - it will call our function and
pass us that `Question`. Then, we can decide whether or not the delete action
link should be shown. Let's show the delete link if `!$question->getIsApproved()`.

[[[ code('2188da90bb') ]]]

Sweet! Let's see what happens. Refresh and... error!

```
Call to a member function getAsDto() on null
```

Boo Ryan. I always do that. Inside `update()`, you need to *return* the action. There
we go, much better!

And now... if we check the menu... look! The "Delete" action is gone! But if you
go down to ID 24 - which is *not* approved - it's there! That's awesome!

## Forbidding Deletes Dynamically

*But*, this isn't *quite* good enough. We're hiding the link on this *one* page
only. And so, we should repeat this for the `DELETE` action on the *detail* page.
And... you may need to disable the delete batch action entirely.

But even *that* wouldn't be enough... because if an admin somehow got the "Delete"
URL for an approved question, the delete action *would* still work. The action
*itself* isn't secure.

To give us that extra layer of security, right before an entity is deleted, let's
check to see if it's approved. And if it *is*, we'll throw an exception.

To test this, temporarily comment-out this logic and `return true`... so that the
delete link *always* shows. Back to the Questions page... got it!

[[[ code('2e5893247b') ]]]

Now go to the bottom of `QuestionCrudController`. Earlier we overrode `updateEntity()`.
This time we're going to override `deleteEntity()`... which will allow us to call
code right *before* an entity is deleted. To help my editor, I'll document that
the entity is going to be an instance of `Question`.

[[[ code('10edad7950') ]]]

Now, `if ($entityInstance->getIsApproved())`, throw a new
`\Exception('Deleting approved questions is forbidden')`. This is going
to look like a 500 Error to the user... so we could also throw an "access denied
exception". Either way, this isn't a situation that anyone should have... unless
we have a bug in our code or a user is trying to do something they shouldn't.
Bad admin user!

[[[ code('26481d8c61') ]]]

I won't try this, but I'm pretty sure it would work. However, this *is* all
a bit tricky! You need to secure the actual *action*... and also make sure that
you remember to hide *all* the links to this action with the correct logic.

Life would be a lot easier if we could, instead, *truly* disable the `DELETE` action
conditionally, on an entity-by-entity basis. If we could do that, EasyAdmin would
hide or show the "Delete" links automatically... and even handle securing the
action if someone guessed the URL.

Is that possible? Yes! We're going to need an event listener and some EasyAdmin
internals. That's next.
