# A Global "Export" Action

There are actually *three* different types of actions in EasyAdmin. The first
consists of the normal actions, like Add, Edit, Delete, and Detail. These operate
on a single entity. The second type is *batch* actions, which operate on a
*selection* of entities. For example, we can click two of these check boxes and
use the Delete button up here. That is the *batch* Delete, and it's the only built-in
batch action.

Side note: to make sure approved questions aren't deleted - which is work we
just finished, you should also remove the batch Delete action for the Question crud.
Otherwise, people might *try* to batch Delete questions. That won't work... thanks
to some code we wrote, but they'll get a very-unfriendly 500 error.

Anyways, the third type of action is called a "global action", which operates on
*all* the entities in a section. There are *no* built-in global actions, but
we're going to add one: a button to "export" the entire questions list to a CSV
file.

## Creating the Global Action

For the most part, creating a global action... isn't much different than creating a
normal custom action. It starts the same. Over in the actions config, create
a new `$exportAction = Action::new()` and call it `export`. Below, we'll
`->linkToCrudAction()` and *also* call it `export`. Then, add some CSS classes...
and an icon. Cool. We're ready to add this to the index page:
`->add(Crud::PAGE_INDEX, $exportAction)` to get that button on the main list page.

[[[ code('143c9a64e4') ]]]

If we stopped now, this would be a *normal* action. When we refresh... yup! It
shows up next to each item in the list. *Not* what we wanted. To make it a
*global* action, back in the action config, call `->createAsGlobalAction()`. You
can also see how you would create a batch action.

[[[ code('7c32bf36d5') ]]]

Now refresh and... awesome!

## Coding up the Custom Action

If we click the new button, we get a familiar error... because we haven't created
that action yet. To help build the CSV file, we're going to install a third party
library. At your terminal, say:

```terminal
composer require handcraftedinthealps/goodby-csv
```

How's that for a great name? The "goodby-csv" library is a well-known CSV package...
but it hasn't been updated for a while. So "handcraftedinthealps" forked it
and made it work with modern versions of PHP. *Super* helpful!

If you downloaded the course code, you *should* have a `tutorial/` directory with a
`CsvExporter.php` file inside. Copy that... and then, in your `src/Service/`
directory, paste. This will handle the heavy lifting of creating the CSV.

[[[ code('6c250b9208') ]]]

At the bottom, this returns a `StreamedResponse` (that's a Symfony response)... that
contains the file download with the CSV data inside. I won't go into the specifics
of how this works... it's all related to the package we installed.

To call this method, we need to pass it three things: the `QueryBuilder` that should
be used to query for the results, the `FieldCollection` (this comes from EasyAdmin
and holds the fields to include), and also the filename that we want to use for the
download. In `QuestionCrudController`, create that `export()` action:
`public function export()`.

[[[ code('ac9eba26e7') ]]]

## Reusing the List Query Builder

Ok, step 1 is to create a QueryBuilder. We could simply inject the
`QuestionRepository`, make a QueryBuilder... and pass that to `CsvExporter`.
But we're going to do something a bit more interesting... and powerful.

When we click the "Export" button, I want to export *exactly* what we see in this
list, including the current *order* of the items *and* any search parameter
we've used to filter the results. To do that, we need to steal some code from our
parent class. Scroll up to the top of the controller... and then hold "cmd" or
"ctrl" to open `AbstractCrudController`. Inside, search for "index". There it is.

So `index()` is the action that renders the list page. And we can see some
logic about how it makes its query. We want to replicate that. Specifically, we
need these three variables: this is where it figures out which fields to show, which
filters are being applied, and ultimately, where it creates the `QueryBuilder`.
Copy those... go back to our `export()` action, and paste. I'll say "Okay"
to add a few `use` statements.

To get this to work, we need a `$context`. That's the `AdminContext` which, as
you probably remember, is something we can autowire as a service into our methods.
Say `AdminContext`... but this time, call it `$context`. Awesome!

[[[ code('985d8aadd8') ]]]

At this point, we have both the `QueryBuilder` and the `FieldCollection` that we
need to call `CsvExporter`. So... let's do it! Autowire `CsvExporter $csvExporter`...
then, at the bottom, it's as simple as
`return $csvExporter->createResponseFromQueryBuilder()` passing `$queryBuilder`,
`$fields`, and then the filename. How about, `questions.csv`

[[[ code('e3f6c5452c') ]]]

Let's try it! Refresh... hit "Export" and... I think it worked! Let me open that
up. Beautiful! We have a CSV of *all* of our data!

## Forwarding Ordering & Filtering Query Params to the Action

But... there *is* one hidden problem. Notice the *ordering* of these items. In
the CSV file... it seems like they're in a random order. But if we look at
the list in the browser, these are ordered by ID! Try searching for something.
Cool. 7 results. But if we export again... and open it... uh oh! We get the
*same* long list of results! So the Search in the CSV export isn't working either!

The problem is this: the search term and any ordering that's currently applied is
reflected in the URL via query parameters. But when we press the "Export" button,
we only get the *basic* query parameters, like which CRUD controller or action is
being called. We do *not* also get the filter, search, or order query parameters.
So then, when we get the `$queryBuilder` and `$filter`, the parameters aren't there
to tell EasyAdmin what filtering and ordering to do!

How can we fix this? By generating a *smarter* URL that *does* include those
query parameters.

Up in `configureActions()`, instead of `->linkToCrudAction()`, let's `->linkToUrl()`
and *completely* take control. Pass this a callback function. Inside, let's
create the URL manually.

[[[ code('9e31193d1d') ]]]

You might remember that, to generate URLs to EasyAdmin, we need the
`AdminUrlGenerator` service. Unfortunately, `configureActions()` isn't a real
action - it's just a random method in our controller - and so we can't
autowire services into it. But no problem: let's autowire what we need into the
constructor.

Add `public function __construct()`... and then autowire
`AdminUrlGenerator $adminUrlGenerator` and also `RequestStack $requestStack`. We're
going to need that in a minute to get the `Request` object. Hit "alt" + "enter"
and go to "Initialize properties" to create both of those properties and set them.

[[[ code('a39e87187d') ]]]

Back down in `configureActions()`... here we go... inside `->linkToUrl()`, get the
request: `$request = $this->requestStack->getCurrentRequest()`. Then, for the URL,
create it from scratch: `$this->adminUrlGenerator`, then
`->setAll($request->query->all()`. This starts by generating a URL that has *all*
of the same query parameters as the current request. Now, override the action -
`->setAction('export')` and then `->generateUrl()`.

[[[ code('2e3c9bd6d9') ]]]

Basically, this says:

> Generate the same URL that I have now... but change the
> action to point to `export`.

Testing time! Refresh the page. We *should* have 7 results. Export, open that up
and... yes! Got it! It shows the *same* results... and in the *same* order as
what we saw on the screen!

Next, let's learn to re-order the actions themselves and generate a URL from our
frontend show page so that we can have an "edit" button right here for admin users.
