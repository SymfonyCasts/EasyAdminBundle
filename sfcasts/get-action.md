# Simple Custom GET Action

Let's add a totally *custom* action. What if, when we're on the detail page for a
question, we add a new action called "view" that takes us to the frontend for that
question? Sounds good! Start in `QuestionCrudController`. To add a new action...
we'll probably need to do some work inside of `configureActions()`. We already know
how to add actions to different pages: with the `->add()` method. Let's try adding,
to `Crud::PAGE_DETAIL`, a new action called `view`.

## Adding the Custom Action in configureActions()

There are a bunch of built-in action names - like `index` or `delete` - and we
usually reference those via their `Action` constant. But in this case, we're making
a *new* action... so let's just "invent" a string called `view`... and see what
happens.

Refresh and... what happened was... an error!

```
The "view" action is not a built-in action, so you can't
add or configure it via its name. Either refer to one of
the built-in actions or create a custom action called "view".
```

In the last chapters, we talked about how, behind-the-scenes, each action is actually
an `Action` object. We don't really think about that most of the time... but when we
create a *custom* action, we need to deal with this object directly.

Above the `return`, create an `Action` object with `$viewAction = Action::new()`...
and pass this the action name that we just invented: `view`. Then, below, instead
of the string, this argument accepts an `$actionNameOrObject`. Pass in that new
`$viewAction` variable.

## Setting the Action to redirect

Refresh again to see... *another* error:

```
Actions must link to either a route, a CRUD action, or a URL.
```

And then it gives us three different methods we can use to set that up. That's
a pretty great error message. It sounds like `linkToRoute()` or `linkToUrl()` is
what we need.

So, up here, let's modify our action. We *could* use `->linkToRoute()`... but as
we learned earlier, that would generate a URL *through* the admin section, complete
with all the admin query parameters. Not what we want. Instead, use
`->linkToUrl()`.

But, hmm. We can't use `$this->generateUrl()` yet... because we need to know
*which* `Question` we're generating the URL for. And we don't have that! Fortunately,
the argument accepts a string or *callable*. Let's try that: pass a `function()`...
and then to see what arguments this receives, let's use a trick:
`dd(func_get_args())`.

Back in the browser... awesome! We are apparently passed *one* argument, which is
the `Question` object. We're dangerous! Use that: `return $this->generateUrl()`,
passing the frontend route name: which is `app_question_show`. This route has
a `slug` route wildcard... so add the `Question $question` argument to the
function and set `slug` to `$question->getSlug()`.

Testing time! And now... yes! We have a "View" button. If we click it... it works!

## Customizing How the Action Looks

And just like any other action, we can modify how this looks. Let's
`->addCssClass('btn btn-success')`, `->setIcon('fa fa-eye`), and
`->setLabel('View on site')`: all things that we've done before for other actions.

Refresh and... that looks great! If we want to include this action on other pages,
we can. Because, if you go to the index page, there's no "view on frontend" action.
Thankfully, we created this nice `$viewAction` variable, so, at the bottom, we
can reuse it: `->add(Crud::PAGE_INDEX, $viewAction)`.

Refresh and... got it! Though... you can see the `btn` styling doesn't really work
well here. I won't do it, but you could clone the `Action` object and then
customize each one.

Okay, so creating an action that links somewhere is cool. But what about a *true*
custom action that connects to a custom controller with custom logic... that does
custom... stuff? Let's add a custom action that allows moderators to approve
questions, next.
