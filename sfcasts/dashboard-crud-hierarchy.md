# Global vs CRUD-Specific Configuration

The methods `configureAssets()`, `configureCrud()`, `configureActions()` and
`configureFilters()` all live here inside of `AbstractCrudController`. And each
gives us a way to control different parts of the CRUD section.

But, these methods *also* live inside of `AbstractDashboardController`. Here's
`configureAssets()`, and then further down, we see the methods for, CRUD, actions
and filters.

But... that doesn't make sense! The dashboard controller just renders... the dashboard
page. And that page doesn't have any actions or any CRUD to configure. What's going
on here?

## One Route for All of your Admin

Click "Questions" and look at the URL. It starts with `/admin` and then has a bunch
of query parameters. It turns out that *everything* in EasyAdmin is handled by a
single giant route. It all runs through the `DashboardController` route - the `/admin`
route that's above `index()`:

[[[ code('45db926fe0') ]]]

So when we go to `QuestionCrudController`, it's actually matching *this* route
here with extra query parameters to say which CRUD controller and which action to run.
You can see `crudController` and `crudAction` hiding in the URL. Yup, we're
rendering `QuestionCrudController`, but in the *context* of `DashboardController`.

And when we go to this page, in order to get the CRUD config, EasyAdmin *first*
calls `configureCrud()` on our *dashboard* controller. *Then* it
calls `configureCrud()` on the specific CRUD controller, in this case,
`QuestionCrudController`. This is *incredibly* powerful. It means that we can
configure things inside of our dashboard - and have those apply to *every* section
in our admin - or configure things inside of one specific CRUD controller to only
change the behavior for that *one* section.

## Understanding Pages and Actions

We can prove it! Go back to `AbstractDashboardController`. Look at `configureCrud()`.
Every CRUD section has 4 pages. Hold `Cmd` or `Ctrl` and click to open this `Crud`
class. Check out the constants on top. Every CRUD section has an index page -
that's this - an edit page, a new page, and also a detail page. Each page can then
have links and buttons to a set of *actions*. For example, on the index
page, right now, we have an action for editing, an action for deleting... and also
an action on top to add a *new* question. And, of course, this is all something
we can control.

You can see how this is configured down in `configureActions()`. Because we're
inside of the *dashboard* controller class, this is the default action configuration
that applies to *every* CRUD section. You can see that, for the index page, it adds
`NEW`, `EDIT` and `DELETE` actions. For the detail page, there's `EDIT`, `INDEX`,
and `DELETE`. And if you're on the edit page, you have the actions `SAVE_AND_RETURN`
and `SAVE_AND_CONTINUE`.

## Adding an Action Globally

If you look closely, you'll notice that while we *do* have a detail page, nobody
links to it! We don't see an action called `DETAIL` on any of these pages. So the
page exists, but it's not really used out-of-the-box. Let's change that!

Go back to `DashboardController`. It doesn't matter where, but I'll go down to the
bottom, go to "Code"->"Generate..." - or `Cmd` + `N` on a Mac - click "Override Methods"
and select `configureActions()`:

[[[ code('5697c1639b') ]]]

We *do* want to call the `parent` method so that it can create the `Actions` object
and set up all of those default actions for us. Let's add a link to the "detail"
page from the "index" page. In EasyAdmin language, this means we want to add
a detail *action* to the index page. Do that by saying `->add()`, passing the
page name - `Crud::PAGE_INDEX` - and then the action: `Action::DETAIL`:

[[[ code('9b9971df4e') ]]]

Thanks to this, when we refresh the index page of `QuestionCrudController`... we
have a "Show" link that goes to the `DETAIL` action! And you'll see this on
*every* section of our admin! Yup, we just modified *every* CRUD controller in the
system!

## Overriding Actions Config for One CRUD

*But*, since the `Topic` entity is so simple, let's disable the `DETAIL` action
for *just* this section. To do that, open up `TopicCrudController`, and, just
like before, go to "Code"->"Generate..." - or `Cmd` + `N` on a Mac - hit "Override Methods"
and select `configureActions()`:

[[[ code('b639f7a187') ]]]

By the time this method is called, it will pass us the `Actions` object that was
already set up by our dashboard. So it will already have the detail action enabled
for the index page. But *now*, we can change that by saying
`->disable(Action::DETAIL)`:

[[[ code('86c2ed695d') ]]]

We'll talk more about the actions configuration later. But these are the main things
that you can do inside of them: add a new action to a page, *or* completely
disable an action. Now, when we refresh, our `DETAIL` action is gone! But if
we go to any other section, it's *still* there.

The big takeaway is that everything is processed *through* our `DashboardController`,
which means that we can configure things on a dashboard-level, which will apply to
all of our CRUDs, *or* we can configure things for one *specific* CRUD.

## Re-Visiting Securing your Admin

The fact that all of the CRUD controllers go through this `/admin` URL has one other
effect related to security. It means that all of our controllers *are* already secure.
That's thanks to our `access_control`.

Remember, back in `config/packages/security.yaml`, we added an `access_control`
that said if the URL starts with `/admin`, require `ROLE_ADMIN`:

[[[ code('75831e240d') ]]]

This means that without doing *anything* else, *every* CRUD controller and action
in our admin already requires `ROLE_ADMIN`. We'll talk more later about how to secure
different admin controllers with different roles... but at the very least, you need
to have `ROLE_ADMIN` to get anywhere, which is awesome.

But one important point: adding this `access_control` *was* necessary. Why? The
`index()` action in our dashboard is what holds the *one* route. When we go to a
CRUD controller, like this, it *does* match this route.... but EasyAdmin does
something crazy. Instead of allowing Symfony to call this controller, it sees this
`crudController` query parameter and magically *switches* the controller to be
the *real* controller. In this case, it changes it to `QuestionCrudController::index()`.

You can see this down on the web debug toolbar. If you hover over "@admin",
this tells you that the matched route name was `admin`. So, yes, the route *is*
matching the main dashboard route. But the controller is
`QuestionCrudController::index()`.

This means that the method in your *CRUD* controller is what Symfony ultimately
executes. In this case, it's the `index()` method in this `AbstractCrudController`...
down here. *This* is the *real* controller for the page.

Why does that matter? First, it's nice to know that, even with all the EasyAdmin
coolness and magic, at the end of the day, the actions in our controller are *real*
actions that are called like any normal action. And second, this is important for
security. Because if we had *only* put the `IsGranted` above `index()` and *not*
added the `access_control`, that would *not* have been enough. Why? Because this
`isGranted` attribute is *only* enforced when you execute *this* action. So, when
we go to the dashboard page.

Anyways, if some of this is still a bit fuzzy, no worries! This was a blast of
EasyAdmin theory that'll help us understand things better as we dig and experiment.

Next, before we go deeper into our CRUD controllers, let's mess around a bit more
with our dashboard by adding some custom links to our admin menu and user menu.
