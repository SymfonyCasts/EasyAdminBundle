# A Global "Export" Action

There are actually *three* different types of actions in EasyAdmin. The first kind
consists of the normal actions, like Add, Edit, Delete, and Detail. These operate
on a single entity. The second type is *batch* actions, which operate on a
*selection* of entities. For example, we can click two of these check boxes and
use the Delete button up here. That is the *batch* Delete, and it's the only built-in
batch action.

Side note: to get make sure approved questions aren't deleted - which is work we
just finished, you should probably remove the batch Delete for the Questions section.
Otherwise, people *might* try to batch Delete questions. That won't work... thanks
to our code, but they'll get a very-unfriendly 500 error.

Anyways, the third type of action is called a "global action", which operates on
*all* the entities in a section. There are *no* built-in global actions, but
we're going to add one: a button to "export" the entire questions list to a CSV
file.

## Creating the Global Action

For the most part, creating a global action isn't much different than creating a
normal custom action. It starts the same. Over in our actions config, create
a new `$exportAction = Action::new()` and call it `export`. Below, we'll
`->linkToCrudAction()` and *also* call it `export`. Then, add some CSS classes...
and an icon. Cool. Now add this to the index page:
`->add(crud::PAGE_INDEX, $exportAction)` so that we get the button on that page.

If we stopperd now, this would be a *normal* action. When we refresh... yup! It
shows up next to each item in the list. *Not* what we wanted. To make it a
*global* action, back in the action config, call `->createAsGlobalAction()`. You
can also see how you would create a batch action.

Now refresh and... awesome!

## Coding up the Custom Action

If we click the new button, we get a familia error because we haven't crated that
action yet. To export a CSV file, we're going to install a third party library to
help. At your terminal, say:

```terminal
composer require handcraftedinthealps/goodby-csv
```

How's that for a great name? The "goodby-csv" libraary is a well-known CSV package,
but it hasn't been updated for a while. So "handcraftedinthealps" forked it
and made it work with modern versions of PHP. *Super* helpful!

If you downloaded the course code, you *should* have a `tutorial/` directory with a
`CsvExporter.php` inside. Copy that... and then, in your `src/Service/` directory,
paste it. This will handle the heavy lifting of exporting the CSV.

At the bottom, this returns a `StreamedResponse` (that's a Symfony response)... that
contains the file download that holds the CSV data. I won't go into the specifics
of how this works right now.

To call this method, we need to pass it three things: The `QueryBuilder` that should
be used to query for the results, the `FieldCollection` (this comes from EasyAdmin
and holds the fields to include), and also the filename that we want to use for the
file download. In `QuestionCrudController`, create that `export()` action:
`public function export()`.

## Reusing the List Query Builder

Ok, step 1 is to create the QueryBuilder. We could simply inject the
`QuestionRepository`, make some QueryBuilder, and pass that to the `CsvExporter`.
But we're going to do something a bit more complex

When we click the "Export" button, I want to export *exactly* what we see in this
list, including the the current *order* of the items *and* any search parameter
we've used to filter the results. To do that, we need to steal some code from our
parent class. Scroll up to the top of this class... and then hold "cmd" to open
`AbstractCrudController`. Inside, search for "index". There it is.

So `index()` is the action that renders the list page. And we can see some
logic about how it makes its query. We want to replicate that. Specifically, we
need these three variables: this is where it figures out which fields to show, which
filters are being applied, and ultimately, where it creates the `QueryBuilder`.
Let's copy those... go back to our `export()` action, and paste. I'll say "Okay"
to add a few `use` statements.

Now, to get this to work, we need a `$context`. That's the `$adminContext` which, as
you probably remember, is something we can autowire as a service into our methods~
Say `AdminContext` and this time, call it `$context`. Awesome!

At this point, we have both the `$queryBuilder` and the `FieldCollection` that we
need to call our `CsvExporter`. So... let's do it! Autowire `CsvExporter $csvExporter`...
then, at the bottom, it's as simple as
`return $csvExporter->createResponseFromQueryBuilder()` passing `$queryBuilder`,
`$fields`, and then the filename: `questions.csv`

Let's try it! Refresh... hit "Export" and... I think it worked! Let me open that
up. Beautiful! We have a CSV of *all* of our data!

## Forwarding Ordering & Filtering Query Params to the Action

But... there *is* one hidden problem.. Notice the *ordering* of these items. In
the CSV file... it seems like they're in some random order. But if we look at
the list in the browser, things are ordered by ID. Let's search for something.
Cool. This should only have 7 results. But if we export again... and open it...
uh oh! we get the *same* long list of results! So the Search in the CSV export
isn't working either!

The problem is this: the search term and any ordering we have is reflected in the
URL via query parameters. But when we press the "Export" button, we only get the
*basic* query parameters, like which CRUD controller or action is being called.
We do *not* also get the filtering, searching, or ordering query parameters. So
then, when get the `$queryBuilder` and `$filter`, the parameters aren't there to
tell EasyAdmin what filtering or ordering to do!

How can we fix this? By generating a *smarter* URL here that *does* include those
parameters.

Up in `configureActions()`, instead of `->linkToCrudAction()`, let's `->linkToUrl()`
and *completely* take control of this. Pass this a callback function. Inside,
we're going to generate the URL manually.

You might remember that, to generate URLs to EasyAdmin, we need its
`AdminUrlGenerator`. Unfortunately, `configureActions()` isn't a real controller
method, so we can't autowire services it. But no problem: let's autowire
what we need into the constructor.
service is by adding a instructor.

Add `public function __construct()`... and then autowire the
`AdminUrlGenerator $adminUrlGenerator` and also `RequestStack $requestStack`. We're
going to need that in a second to get the `Request` object. I'll hit "alt" + "enter"
and go to "Initialize properties" to create both of those properties and set them.

Back down in `configureActions()`... here we go... inside `->linkToUrl()`, get the
request: `$request = $this->requestStack->getCurrentRequest()`. Then, for the URL,
create it from scratch: `$this->adminUrlGenerator` and
`->setAll($request->query->all()`. This starts by generating a URL that has *all*
of the same query parameters as the current request. Now, override the action -
`->setAction('export')` and then `->generateUrl()`.

Basically, what this says is:

> Generate the same URL that I have now... but change the
> action to point to `export`.

Testing time! Refresh the page. We *should* have 7 results. Export the list, open
that up and... yes! Got it! It shows the *same* results... and in the *same* order
as what was on the screen!

Next, let's learn to re-order the actions themselves and generate a URL from our
frontend show page so that we can have an "edit" button right here for admin users.
