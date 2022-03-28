# Global Action

There are actually *three* different types of actions in EasyAdmin. The first kind are the normal actions like Add, Edit, Delete, and Show, and they operate on a single entity. The second kind is batch actions, which operate on a set selection of entities. So I can click on two of these check boxes and I have a Delete button up here. That is the *batch* Delete, and it's the only built-in batch action.

Side note: To get things to work properly, you should probably remove the batch Delete for the Questions page. Otherwise, people *might* try to batch Delete questions, which won't work. Thanks to our code, they'll get a 500 Error.

The third type of action is called a "global action", which operates on *all* the entities in the section. There are no built-in global actions, but we're going to add one to export this Question list to a CSV file. For the most part, creating a global action isn't much different than creating a normal custom action. It starts the same way. Over in our actions config, create a new `$exportAction = Action::new()` and call it `export`. Below, we'll `->linkToCrudAction()` and *also* call it `export`. Then, we'll add some CSS classes, `->addCssClass('btn btn-success')`, and an icon, `->setIcon('fa fa-download')`. Below, let's add this to our index page, which is the page where it makes most sense to add a global action: `->add(crud::PAGE_INDEX, $exportAction)`. Beautiful!

If we stop now, this is actually just a normal action. If I refresh... it's showing up next to each item over here, which is *not* what we want. To make it a *global* action, back up in the action, call `->createAsGlobalAction()`. You can also see how you would create a batch action here. Now when we refresh... awesome!

If you click our new button, we get an error because we haven't made that action yet. To export to CSV, we're going to install a third party library to help. At your terminal, say:

```terminal
composer require handcraftedinthealps/goodby-csv
```

How's that for a great name? The "goodby-csv" is a well-known CSV package, but it hasn't been updated for a while, so "handcraftedinthealps" has forked that and made it work with modern versions of PHP. *Super* helpful!

If you downloaded the course code, you *should* have a `/tutorial` directory with a `CsvExporter.php` inside. Copy that... and then, in your `/src/Service` directory, paste it. This is going to handle the heavy lifting of exporting the CSV for us.

At the bottom, this is going to return a `StreamedResponse` (that's a Symfony response), but the response will actually contain a file download that contains the CSV. I won't go into the specifics of how this works right now.

To call this method, we need to pass it three things: The `QueryBuilder` (that should be used to query for the results), the `FieldCollection` (that comes from EasyAdmin and it's the fields to include), and also the file name that we want to use for the file download. In `QuestionCrudController.php`, let's create that export action: `public function export()`. If you wanted to, you could simply inject the `QuestionRepository` here, do whatever query you want to return the questions, and pass that through the CSV system. But we're going to do something a bit more complex.

When we click this "Export" button, I want to export *exactly* what we see in this list, including the current order of items in the list, *or* if we have used a particular search parameter to display specific results. To do that, we need to steal some code from our parent class. I'll scroll up to the top of this class, and then hold "cmd" to open `AbstractCrudController`. Inside of this, search for "index". There it is.

So, `index` is what actually renders the list page, and we can see a little bit of logic about how it makes its query. We want to replicate that. The key things we need now, down here, are these three fields. This is where it figures out which fields to show, which filters are being applied, and ultimately, it creates the `QueryBuilder`. Let's copy that... go back to our export action, and paste. I'll say "Okay" to add a couple of "use" statements.

Now, to get this to work, we need a `$context`. That's the `$adminContext` which, as you probably remember, is something that we can autowire as a service into our methods. Say `AdminContext` and this time, call it `$context`. Awesome!

At this point, we have the `$queryBuilder` and the `FieldCollection` that we need to call our `CsvExporter`. So let's do it! Autowire `CsvExporter $csvExporter` here, and then, at the bottom, it's as simple as `return $csvExporter->createResponseFromQueryBuilder()`. Then, we'll pass that the three arguments, which is the `$queryBuilder`,`$fields`, and then some file name. I'll just say `questions.csv`.

All right, let's try it. I'll refresh... hit "Export" and... looks like it worked! Let me open that up. Beautiful! We have a CSV of all of our data, but there *is* one little hidden thing here that's not working. Notice the ordering of these items. They appear to be in some random order in the CSV file, but if we look at the actual list, I had things ordered by ID. And let's search for something. This should only have seven results, but if we export that and open it... yep. We get the *same* bunch of results. So the Search aspect of this CSV export isn't working either.

The problem is this: When we click this export button on this page, the "all" search term and *any* ordering we have is reflected in the URL via query parameters. But when we press the "Export" button, we just get the *basic* query parameters, like which CRUD controller or action is being called, but any filtering, searching, or ordering of those query parameters are not included. So when we call `$queryBuilder` and `$filter`, the parameters aren't there to tell it to do the filtering or ordering. To do that, we're going to generate a *smarter* URL here that *does* include those parameters.

Up in our `configureActions`, instead of `->linkToCrudAction()`, we're going to say `->linkToUrl()` and completely take control of this. Pass this a callback, `function()`, and inside of here, we're going to generate the URL manually. You might remember that to generate URLs to EasyAdmin, we need the `AdminUrlGenerator`. Right now, I'm inside of `configureActions`. This isn't a method we can autowire services into since it's not a real action, so I'm going to get these service is by adding a instructor.

Let's say `public function __construct()` and then autowire the `AdminUrlGenerator $adminUrlGenerator`, and also the `RequestStack $requestStack`. We're going to need that in a second to get the request object. I'll hit "alt" + "enter" and go to "Initialize properties" to create both of those properties and set them. Back down in `configureActions`... here we go... inside of `->linkToUrl()`, get the request: `$request = $this->requestStack->getCurrentRequest()`. Then, for the URL, we'll just create it from scratch: `$this->adminUrlGenerator` and `->setAll($request->query->all()`. This starts generating a URL that has *all* of the same query parameters as the current request. And now, we'll just override a few things: `->setAction('export')` and then say `->generateUrl()`.

Basically, what this says is

```
Generate the same URL that I have now, but change the
action to point to our new export.
```

All right, let's try this. I'll just refresh this page that the new URL generates. And as I mentioned, we *should* have about seven results. Export the list, open that up and... yes! Got it! It shows the *same* results here in the *same* order that we see over here.

Next, let's learn to re-order the actions themselves and generate a URL from our frontend Show page so we can have a button right here that links directly to the Edit action of this entity in our admin.
