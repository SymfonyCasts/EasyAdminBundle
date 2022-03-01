# configureCrud()

If you look at the index page of any of the crud sections, it doesn't sort by default.
It just... lists things in whatever order they come out of the database. It would
nice to *change* that: to sort by a specific column whenever we go to a section.

So far, we've talked about configuring assets, fields and actions. But "how a crud
section sorts"... doesn't fall into any of these categories. Nope, for the first
time, we need to configure something on the "crud section" as a whole.

Go to one of your crud controllers and open its base class. We know that there are
a number of methods that we can override to control things... and we've already
done that for many of these. But we have *not* yet used `configureCrud()`. As its
name suggests, this is all about controlling settings on the entire crud *section*.

And *just* like with most of the other methods, we can override this in our dashboard
controller to make changes to *all* crud sections, or override it in one specific
crud controller.

## Sorting All Crud Sections

Let's see if we can set the default sorting across our entire *admin* to sort by id
descending. To do that, open `DashboardController` and, anywhere inside, go to
Code -> Generate - or command+N on a Mac - select override methods and choose
`configureCrud()`.

[[[ code('5a9be0f658') ]]]

The `Crud` object has a bunch of methods on it... including one called
`setDefaultSort()`. That sounds handy! Pass that the array of the fields we want
to sort by. So, `id` set to `DESC`.

[[[ code('b31bd2ec70') ]]]

Back over at the browser, when we click on "Questions"... beautiful! By default,
it now sorts by id. Really, *all* sections now sort by id.

## Sorting Differently in One Section

And what if we want to sort Questions in a different way than the default? I bet
you already know how we would do that. So let's make things more interesting. Every
`Question` is owned by a `User`. What if we wanted to show the questions whose
users are enabled first? Can we do that?

First, since we want to only apply this to the questions section, we need to make
this change inside of `QuestionCrudController`. Go to the bottom and... same thing:
override `configureCrud()`... and call the exact same method as before:
`setDefaultSort()`. Now we can say, `askedBy` - that's the property on `Question`
that is a relation to `User` - `askedBy.enabled`. Set this to `DESC`.

And then, after sorting by enabled first, sort the rest by `createdAt` `DESC`.

[[[ code('ad3b704379') ]]]

Let's check it! Click "Questions". Because we're sorting across multiple
fields, you don't see any header highlighted as the currently-sorted column. But...
it looks right, at least based on the "Created At" dates.

To know for sure, click the database link on the web debug toolbar. Then... search
this page for "ORDER BY". There it is! Click "View formatted query". And...yes!
It's ordering by `user.enabled` and then `createdAt`. Pretty cool.

## Disabling Sorting on a Field

So we now have default sorting... though the user can, of course, still click any
header to sort by whichever column they want. But sometimes, sorting by a specific
field doesn't make sense! You can see that it's already disabled for "answers".

And if we go over to... let's see... the Users section, there's also no sort for
the avatar field, which *also* seems reasonable.

If you want to control this a bit further, you *can*. Like, let's pretend that we
don't want people sorting by the name of the question. This is something we can
configure on the field itself.

In `QuestionCrudController`, for the name field, call `setSortable(false)`.

[[[ code('3f02f6b710') ]]]

And... just like that, the arrow is gone.

## Inlining Controls for an Admin Section

Before we move on - because there isn't a *ton* that we need to control on the
CRUD-level, let me show you one more thing. Head to the Topics section. This entity
is really *simple*... so we have plenty of space here to show these fields.

Normally, the "actions" on the index page are hidden under this dropdown. But, we
*can* render them inline.

To do that, head to `TopicCrudController`... go down... and override
`configureCrud()`. On the `Crud` object, call `->showEntityActionsInlined()`.

[[[ code('0fe15e944c') ]]]

That's it. Now... yea! That looks better.

Next: I want to talk about using a what-you-see-is-what-you-get editor. There's
actually a simple one built *into* Easy Admin. But we're going to go further and
install our own. Doing *that* will require some custom JavaScript.
