# Custom Controller & Generating Admin URLs

The final step to building our custom EasyAdmin action is to... *write* the
controller method! In `QuestionCrudController`, all the way down at the bottom,
this will be a *normal* Symfony action. You can pretend like you're writing this
in a non-EasyAdmin controller class with a route above it. Say
`public function approve()`. When the user gets here, the `id` of the entity will
be in the URL. To help read that, autowire `AdminContext $adminContext`.

[[[ code('0805766b12') ]]]

Why are we allowed to add that argument? Because first, `AdminContext` is a service...
just like the entity manager or the router. And second, the `approve()` method is
a completely normal Symfony controller... so we're autowiring this service just
like we would do with anything else.

Get the question with `$question = $adminContext->getEntity()->getInstance()`.
And yes, sometimes, finding the data you need in `AdminContext` requires a little
digging. Let's add a sanity check... (mostly for my editor):
`if (!$question instanceof Question)`, throw a new
`\LogicException('Entity is missing or not a Question')`.
Now, we can very easily say `$question->setIsApproved(true)`.

[[[ code('defbfa841b') ]]]

The last step is to save this entity... which looks completely normal! Autowire
`EntityManagerInterface $entityManager`... and then add `$entityManager->flush()`.

[[[ code('657c8ad773') ]]]

## Rendering a Template

Sweet! Ok... but... what should we *do* after that? Well, we *could* render a template.
Sometimes you'll create a custom action that is literally a new page in your admin
section... and you would do that by rendering a template in a completely normal way.
We already have an example of that inside `DashboardController`. The `index()` method
is *really* a regular action... where we render a template. So if you wanted to render
a template in a custom action, it would look pretty much exactly like this.

# Generating an Admin Url

But in *our* situation, we want to redirect. And, we know how to do that from inside
of a controller. But hmm, I want to redirect back to the "detail" page in the admin.
In order to generate a URL to somewhere inside EasyAdmin, we need a special admin
URL generator service that can help add the query parameters.

Let's autowire this: `AdminUrlGenerator $adminUrlGenerator`. Then
`$targetUrl =`... and build the URL by saying `$adminUrlGenerator`,
`->setController(self::class)` - because we're going to link back to ourself -
`->setAction(Crud::PAGE_DETAIL)`, `->setEntityId($question->getId())`... and
then *finally*, `->generateUrl()`.

There are a number of other methods you can call on this builder... but these are
the most important. At the bottom `return $this->redirect($targetUrl)`.

[[[ code('3a8b58628c') ]]]

Ok team, let's give this a try. Refresh and... got it! We're back on the detail
page! And if we look for "Alice thought she might...", it's not on our
"Pending Approval" page anymore!

Let's try one more to be sure: approve ID 23. Go to Show, click "Approve", and...
it's *gone*. This is working!

## Hiding Approve for Approved Question

The only weird thing now, which you *probably* saw, is that when you go to the detail
page on an *already-approved* question... you still see the "Approve" button. Clicking
on that doesn't hurt anything... but it's confusing! Fortunately, we know how to
fix this.

Find your custom action... and add `->displayIf()`. Pass that a `static function()`,
which will receive the `Question $question` argument... and return a `bool`.
I've been a little lazy on my return types, but you can put that if you want. Finally,
`return !$question->getIsApproved()`.

[[[ code('1ac115e950') ]]]

Move over now... refresh and... beautiful! The "Approve" button is *gone*. But
when we go back to a question that *does* need to be approved, it's *still* there.

## Custom Action JavaScript

If we wanted to, we could go further and write some JavaScript to make this fancier.
For example, in our custom template, we could use the `stimulus_controller` function
to reference a custom Stimulus controller. Then, when we click this button, we
could, for example, open a modal that says:

> Are you sure you want to approve this question?

The point is, *we* control what this action, link, button, etc. look like. If
you want to attach some custom JavaScript, do it.

Next, let's add a *global* action. A "global action" is something that applies to
*all* of the items inside of a section. We're going to create a global *export* action
that exports questions to CSV.
