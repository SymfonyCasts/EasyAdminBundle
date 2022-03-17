# Multiple Cruds for a Single Entity?

Right now, we have one CRUD controller per entity. But we *can* create *more* than
one CRUD controller for the *same* entity. Why would this be useful? Well, for
example, we're going to create a separate "Pending Approval" questions section that
*only* lists questions that need to be approved.

Ok, so, we need a new CRUD controller. Instead of generating it this time, let's
create it by hand. Call the class `QuestionPendingApprovalCrudController`. We're
making this by hand because, instead of extending the normal base class for a CRUD
controller, we'll extend `QuestionCrudController`. That way, it inherits *all* the
normal `QuestionCrudController` config and logic.

[[[ code('0d47e7a45e') ]]]

## Linking to the Controller and setController()

Done! Step two: whenever we add a new CRUD controller, we need to link to it from
our dashboard. Open `DashboardController`... duplicate the question menu item...
say "Pending Approval"... and I'll tweak the icon.

[[[ code('766d0edad5') ]]]

If we stopped now, you might be thinking:

> Wait a second! Both of these menu items simply point to the `Question` entity.
> How will EasyAdmin know which controller to go to?

This definitely *is* a problem. The truth is that, when we have multiple CRUD
controllers for the same entity, EasyAdmin  *guesses* which to use. To tell it
explicitly, add `->setController()` and then pass it
`QuestionPendingApprovalCrudController::class`.

[[[ code('872020642d') ]]]

Do we need to set the controller on the other link to be safe? Absolutely. And we'll
do that in a few minutes.

But let's try this. Refresh. We get two links... and each section looks absolutely
identical, which makes sense. Let's modify the query for the new section to only
show *non-approved* questions. And... we already know how to do that!

Over in the new controller, override the method called `createIndexQueryBuilder()`.
Then we'll just modify this: `->andWhere()` and we know that our entity alias
is always `entity`. So `entity.isApproved` (that's the field on our `Question` entity)
`= :approved`... and then `->setParameter('approved', false)`.

[[[ code('b52430ba65') ]]]

Let's try it! We go from a *bunch* question to... just *five*. It works! Except that
if you go to the original Question section... that *also* only shows five!

Yup, it's guessing the *wrong* CRUD controller. So in practice, as soon as you have
multiple CRUD controllers for an entity, you should *always* specify the controller
when you link to it. For this one, use `QuestionCrudController::class`.

[[[ code('5aa7392e4e') ]]]

If we head over and refresh this page... there's no difference! That's because
we modified the *link*... but we're already *on* the page for the *new* CRUD
controller. So click the link and... much better!

## Including Entity Data in the Page Title

Let's tweak a few things on our new CRUD controller. Override
`configureCrud()`. Most importantly, we should `->setPageTitle()` to set the
title for `Crud::PAGE_INDEX` to "Questions Pending Approval".

[[[ code('6b0fde7ee1') ]]]

Now... it's *much* more obvious which page we're on.

Oh, and when we set the page title, we can actually pass a *callback* if we want
to use the `Question` object itself in the name... assuming you're setting the page
title for the detail or edit pages where you're working with a *single* entity.

Check it out: call `->setPageTitle()` again, and set *this* one for
`Crud::PAGE_DETAIL`. Then, instead of a string, pass a callback: a `static function`
that will receive the `Question` object as the first argument. Inside, we can
return whatever we want: how about `return sprintf()` with `#%s %s`...
passing `$question->getId()` and `$question->getName()` as the wildcards.

[[[ code('2f34fba7fd') ]]]

Let's check it! Head over to the detail page for one of these questions and... awesome!
Dynamic data in the title.

And while we're here, I also want to add a "help" message to the index page:

> Questions are not published to users until approved by a moderator

[[[ code('e113305583') ]]]

When we refresh... our message shows up right next to the title!

## Autocomplete() and Multiple CRUD Controllers

Okay, there's one more subtle problem that having two CRUD controllers has just
created. To see it, jump into `AnswerCrudController`. Find the `AssociationField`
for `question`... and add `->autocomplete()`... which it needs because there's going
to be *a lot* of questions in our database.

[[[ code('3fe6c45368') ]]]

If we look at our main Questions page... this first question is *probably* an
approved question - since most are - so I'll copy part of its name. Now go to
Answers, edit an answer... and go down to the Question field. This uses
autocomplete, which is cool! But if I paste the string, it says "No results found"?

The reason is subtle. Go down to the web debug toolbar and open the profiler for
one of those autocomplete AJAX requests. Look at the URL closely... part of it
says "crudController = QuestionPendingApprovalCrudController"!

When an autocomplete AJAX request is made for an entity (in this case, it's trying to
autocomplete Question), that AJAX request is done *by* a CRUD controller. If you jump
into `AbstractCrudController`... there's actually an `autocomplete()` action. This
is the action that's called to create the autocomplete response. It's done this way
so that the autocomplete results can reuse your index query builder. Unfortunately,
just like with our dashboard links, the autocomplete system is *guessing* which
of our two CRUD controllers to use for Question... and it's guessing wrong.

To fix this, once again, we just need to be explicit. Add
`->setCrudController(QuestionCrudController::class)`.

[[[ code('56bdcbec61') ]]]

This time, I'll refresh... go down to the Question field, search for the string
and... it finds it!

Next, what if we want to run some code before or after an entity is updated, created,
or deleted? EasyAdmin has two solutions: Events and controller methods.
